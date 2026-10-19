<?php

namespace App\Back\Repositories;

use App\Back\DTOs\StockExitDTO;
use Core\Database;
use Throwable;

final class StockMovementRepository
{
  public function __construct(
    private SectorRepository $sectorRepository = new SectorRepository()
  ) {}

  public function registerExit(StockExitDTO $exit): bool
  {
    $connection = Database::connection();

    try {
      $connection->beginTransaction();

      if (!$this->sectorRepository->isActive($exit->sectorId)) {
        $connection->rollBack();
        return false;
      }

      $update = $connection->prepare(
        'UPDATE estoque_materiais
         SET estoque_central = estoque_central - :quantidade
         WHERE id = :material_id
           AND ativo = 1
           AND estoque_central >= :quantidade_disponivel'
      );
      $update->execute([
        'quantidade' => $exit->quantity,
        'material_id' => $exit->materialId,
        'quantidade_disponivel' => $exit->quantity,
      ]);

      if ($update->rowCount() !== 1) {
        $connection->rollBack();
        return false;
      }

      $insert = $connection->prepare(
        'INSERT INTO estoque_movimentacoes
          (data, material_id, tipo, quantidade, setor_id, pedido_id, observacao)
         VALUES
          (:data, :material_id, \'saida\', :quantidade, :setor_id, NULL, :observacao)'
      );
      $insert->execute([
        'data' => $exit->date . ' 00:00:00',
        'material_id' => $exit->materialId,
        'quantidade' => $exit->quantity,
        'setor_id' => $exit->sectorId,
        'observacao' => $exit->observation,
      ]);

      $possessionStatement = $connection->prepare(
        'SELECT id
         FROM estoque_posses
         WHERE material_id = :material_id AND setor_id = :setor_id
         FOR UPDATE'
      );
      $possessionStatement->execute([
        'material_id' => $exit->materialId,
        'setor_id' => $exit->sectorId,
      ]);
      $possessionId = $possessionStatement->fetchColumn();

      if ($possessionId === false) {
        $possessionUpdate = $connection->prepare(
          'INSERT INTO estoque_posses
            (material_id, setor_id, quantidade, ultima_saida)
           VALUES (:material_id, :setor_id, :quantidade, :ultima_saida)'
        );
        $possessionUpdate->execute([
          'material_id' => $exit->materialId,
          'setor_id' => $exit->sectorId,
          'quantidade' => $exit->quantity,
          'ultima_saida' => $exit->date . ' 00:00:00',
        ]);
      } else {
        $possessionUpdate = $connection->prepare(
          'UPDATE estoque_posses
           SET quantidade = quantidade + :quantidade,
               ultima_saida = CASE
                 WHEN ultima_saida IS NULL OR ultima_saida < :ultima_saida_comparison
                   THEN :ultima_saida_value
                 ELSE ultima_saida
               END
           WHERE id = :id'
        );
        $possessionUpdate->execute([
          'quantidade' => $exit->quantity,
          'ultima_saida_comparison' => $exit->date . ' 00:00:00',
          'ultima_saida_value' => $exit->date . ' 00:00:00',
          'id' => (int) $possessionId,
        ]);
      }

      $connection->commit();
      return true;
    } catch (Throwable $exception) {
      if ($connection->inTransaction()) {
        $connection->rollBack();
      }

      throw $exception;
    }
  }
}

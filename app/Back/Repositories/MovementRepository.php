<?php

namespace App\Back\Repositories;

use App\Back\DTOs\MovementFilterDTO;
use App\Back\Models\StockMovement;
use Core\Database;

final class MovementRepository
{
  /**
   * @return StockMovement[]
   */
  public function findAll(MovementFilterDTO $filters): array
  {
    $sql = 'SELECT
              movimento.id,
              movimento.data,
              movimento.tipo,
              movimento.quantidade,
              movimento.observacao,
              material.codigo AS material_codigo,
              material.nome AS material_nome,
              COALESCE(categoria.nome, \'Sem categoria\') AS categoria_nome,
              setor.nome AS setor_nome,
              pedido.numero AS pedido_numero
            FROM estoque_movimentacoes movimento
              INNER JOIN estoque_materiais material ON material.id = movimento.material_id
              LEFT JOIN estoque_categorias categoria ON categoria.id = material.categoria_id
              LEFT JOIN estoque_setores setor ON setor.id = movimento.setor_id
              LEFT JOIN estoque_pedidos pedido ON pedido.id = movimento.pedido_id';
    $conditions = [];
    $parameters = [];

    if ($filters->search !== '') {
      $conditions[] = '(material.codigo LIKE :search_code OR material.nome LIKE :search_name OR movimento.observacao LIKE :search_observation OR pedido.numero LIKE :search_order)';
      $search = '%' . $filters->search . '%';
      $parameters['search_code'] = $search;
      $parameters['search_name'] = $search;
      $parameters['search_observation'] = $search;
      $parameters['search_order'] = $search;
    }

    if ($filters->type !== '') {
      $conditions[] = 'movimento.tipo = :type';
      $parameters['type'] = $filters->type;
    }

    if ($filters->sectorId !== null) {
      $conditions[] = 'movimento.setor_id = :sector_id';
      $parameters['sector_id'] = $filters->sectorId;
    }

    if ($filters->categoryId !== null) {
      $conditions[] = 'material.categoria_id = :category_id';
      $parameters['category_id'] = $filters->categoryId;
    }

    if ($filters->dateFrom !== '') {
      $conditions[] = 'movimento.data >= :date_from';
      $parameters['date_from'] = $filters->dateFrom . ' 00:00:00';
    }

    if ($filters->dateTo !== '') {
      $conditions[] = 'movimento.data < :date_to';
      $parameters['date_to'] = date('Y-m-d', strtotime($filters->dateTo . ' +1 day')) . ' 00:00:00';
    }

    if ($conditions !== []) {
      $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY movimento.data DESC, movimento.id DESC';
    $statement = Database::connection()->prepare($sql);
    $statement->execute($parameters);

    return array_map(
      static fn(array $row): StockMovement => new StockMovement(
        (int) $row['id'],
        $row['data'],
        $row['material_codigo'],
        $row['material_nome'],
        $row['categoria_nome'],
        $row['tipo'],
        (int) $row['quantidade'],
        $row['setor_nome'],
        $row['observacao'],
        $row['pedido_numero']
      ),
      $statement->fetchAll()
    );
  }
}

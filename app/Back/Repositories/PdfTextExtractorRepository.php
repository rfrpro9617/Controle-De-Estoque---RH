<?php

namespace App\Back\Repositories;

use Core\Database;

final class PdfTextExtractorRepository
{
  public function insertCategory(string $category): int
  {
    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_categorias (nome)
       VALUES (:nome)
       ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), nome = VALUES(nome)'
    );

    $stmt->execute(['nome' => trim($category)]);

    return (int) Database::connection()->lastInsertId();
  }

  public function insertMaterial(string $category, string $description, string $code): int
  {
    $categoryId = $this->insertCategory($category);

    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_materiais (categoria_id, nome, codigo)
       VALUES (:categoria_id, :nome, :codigo)
       ON DUPLICATE KEY UPDATE
         id = LAST_INSERT_ID(id),
         categoria_id = VALUES(categoria_id),
         nome = VALUES(nome),
         codigo = VALUES(codigo)'
    );

    $stmt->execute([
      'categoria_id' => $categoryId,
      'nome' => trim($description),
      'codigo' => trim($code),
    ]);

    return (int) Database::connection()->lastInsertId();
  }

  public function insertSupplier(string $supplier): int
  {
    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_fornecedores (nome)
       VALUES (:nome)
       ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), nome = VALUES(nome)'
    );

    $stmt->execute(['nome' => trim($supplier)]);

    return (int) Database::connection()->lastInsertId();
  }

  public function insertPurchaseOrder(string $number, string $date, int $supplierId): int
  {
    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_pedidos (numero, data, fornecedor_id)
       VALUES (:numero, :data, :fornecedor_id)
       ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)'
    );
    $stmt->execute([
      'numero' => trim($number),
      'data' => $date,
      'fornecedor_id' => $supplierId,
    ]);

    return (int) Database::connection()->lastInsertId();
  }

  public function insertPurchaseOrderItem(
    int $purchaseOrderId,
    int $materialId,
    int $quantity,
    float $unitPrice
  ): bool {
    $connection = Database::connection();
    $stmt = $connection->prepare(
      'INSERT INTO estoque_pedido_itens
        (pedido_id, material_id, quantidade, valor_unitario, quantidade_recebida)
       VALUES (:pedido_id, :material_id, :quantidade, :valor_unitario, :quantidade_recebida)'
    );

    $inserted = $stmt->execute([
      'pedido_id' => $purchaseOrderId,
      'material_id' => $materialId,
      'quantidade' => $quantity,
      'valor_unitario' => $unitPrice,
      'quantidade_recebida' => $quantity,
    ]);

    if (!$inserted) {
      throw new \RuntimeException('Não foi possível salvar o item do pedido importado.');
    }

    $updateStock = $connection->prepare(
      'UPDATE estoque_materiais
       SET estoque_central = estoque_central + :quantidade
       WHERE id = :material_id'
    );
    $updateStock->execute([
      'quantidade' => $quantity,
      'material_id' => $materialId,
    ]);

    if ($updateStock->rowCount() !== 1) {
      throw new \RuntimeException('Não foi possível atualizar o estoque do material importado.');
    }

    $movement = $connection->prepare(
      'INSERT INTO estoque_movimentacoes
        (material_id, tipo, quantidade, pedido_id, observacao)
       VALUES (:material_id, \'entrada\', :quantidade, :pedido_id, :observacao)'
    );
    $movement->execute([
      'material_id' => $materialId,
      'quantidade' => $quantity,
      'pedido_id' => $purchaseOrderId,
      'observacao' => 'Recebimento registrado na importação do pedido.',
    ]);

    return $inserted;
  }

  public function hasPurchaseOrderItems(int $purchaseOrderId): bool
  {
    $statement = Database::connection()->prepare(
      'SELECT 1 FROM estoque_pedido_itens WHERE pedido_id = :pedido_id LIMIT 1'
    );
    $statement->execute(['pedido_id' => $purchaseOrderId]);

    return $statement->fetchColumn() !== false;
  }
}

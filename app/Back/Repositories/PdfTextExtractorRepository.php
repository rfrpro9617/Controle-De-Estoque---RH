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
    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_pedido_itens (pedido_id, material_id, quantidade, valor_unitario)
       VALUES (:pedido_id, :material_id, :quantidade, :valor_unitario)'
    );

    return $stmt->execute([
      'pedido_id' => $purchaseOrderId,
      'material_id' => $materialId,
      'quantidade' => $quantity,
      'valor_unitario' => $unitPrice,
    ]);
  }
}

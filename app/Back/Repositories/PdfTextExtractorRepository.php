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

  public function insertMaterial(string $category, string $description, string $code): bool
  {
    $categoryId = $this->insertCategory($category);

    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_materiais (categoria_id, nome, codigo)
       VALUES (:categoria_id, :nome, :codigo)
       ON DUPLICATE KEY UPDATE
         categoria_id = VALUES(categoria_id),
         nome = VALUES(nome),
         codigo = VALUES(codigo)'
    );

    return $stmt->execute([
      'categoria_id' => $categoryId,
      'nome' => trim($description),
      'codigo' => trim($code),
    ]);
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
}

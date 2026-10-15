<?php

namespace App\Back\Repositories;

use App\Back\Models\Supplier;
use Core\Database;

final class SupplierRepository
{
  /**
   * @return Supplier[]
   */
  public function findAll(): array
  {
    $stmt = Database::connection()->query(
      'SELECT id, nome, ativo
       FROM estoque_fornecedores
       ORDER BY nome ASC, id ASC'
    );

    return array_map(
      fn(array $row) => new Supplier(
        (int) $row['id'],
        $row['nome'],
        (int) $row['ativo'] === 1
      ),
      $stmt->fetchAll()
    );
  }
}

<?php

namespace App\Back\Repositories;

use App\Back\Models\Category;
use Core\Database;

final class CategoryRepository
{
  /**
   * @return Category[]
   */
  public function findAll(): array
  {
    $stmt = Database::connection()->query(
      'SELECT categoria.id, categoria.nome, categoria.descricao, categoria.ativo,
              (SELECT COUNT(*)
               FROM estoque_materiais material
               WHERE material.categoria_id = categoria.id) AS quantidade_materiais
       FROM estoque_categorias categoria
       ORDER BY categoria.nome ASC, categoria.id ASC'
    );

    return array_map(
      fn(array $row) => new Category(
        (int) $row['id'],
        $row['nome'],
        $row['descricao'],
        (int) $row['ativo'] === 1,
        (int) $row['quantidade_materiais']
      ),
      $stmt->fetchAll()
    );
  }
}

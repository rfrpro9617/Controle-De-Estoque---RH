<?php

namespace App\Back\Repositories;

use App\Back\DTOs\StockFilterDTO;
use App\Back\Models\StockMaterial;
use App\Back\Utils\StockStatus;
use Core\Database;

final class StockRepository
{
  /**
   * @return StockMaterial[]
   */
  public function findAll(StockFilterDTO $filters): array
  {
    $sql = 'SELECT
              material.id,
              material.codigo,
              material.nome,
              COALESCE(categoria.nome, \'Sem categoria\') AS categoria_nome,
              material.estoque_central,
              material.estoque_minimo,
              material.ativo
            FROM estoque_materiais material
              LEFT JOIN estoque_categorias categoria
                ON categoria.id = material.categoria_id';
    $conditions = [];
    $parameters = [];

    if ($filters->search !== '') {
      $conditions[] = '(material.codigo LIKE :search_code OR material.nome LIKE :search_name OR categoria.nome LIKE :search_category)';
      $search = '%' . $filters->search . '%';
      $parameters['search_code'] = $search;
      $parameters['search_name'] = $search;
      $parameters['search_category'] = $search;
    }

    if ($filters->categoryId !== null) {
      $conditions[] = 'material.categoria_id = :category_id';
      $parameters['category_id'] = $filters->categoryId;
    }

    if ($filters->status !== '') {
      $conditions[] = StockStatus::sqlExpression() . ' = :status';
      $parameters['status'] = $filters->status;
    }

    if ($conditions !== []) {
      $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY material.nome ASC, material.id ASC';
    $statement = Database::connection()->prepare($sql);
    $statement->execute($parameters);

    return array_map(
      static fn(array $row): StockMaterial => new StockMaterial(
        (int) $row['id'],
        $row['codigo'],
        $row['nome'],
        $row['categoria_nome'],
        (int) $row['estoque_central'],
        (int) $row['estoque_minimo'],
        (int) $row['ativo'] === 1
      ),
      $statement->fetchAll()
    );
  }

  /**
   * @return StockMaterial[]
   */
  public function findActiveForExit(): array
  {
    $statement = Database::connection()->query(
      'SELECT
         material.id,
         material.codigo,
         material.nome,
         COALESCE(categoria.nome, \'Sem categoria\') AS categoria_nome,
         material.estoque_central,
         material.estoque_minimo,
         material.ativo
       FROM estoque_materiais material
         LEFT JOIN estoque_categorias categoria
           ON categoria.id = material.categoria_id
       WHERE material.ativo = 1
         AND material.estoque_central > 0
       ORDER BY material.nome ASC, material.id ASC'
    );

    return array_map(
      static fn(array $row): StockMaterial => new StockMaterial(
        (int) $row['id'],
        $row['codigo'],
        $row['nome'],
        $row['categoria_nome'],
        (int) $row['estoque_central'],
        (int) $row['estoque_minimo'],
        (int) $row['ativo'] === 1
      ),
      $statement->fetchAll()
    );
  }
}

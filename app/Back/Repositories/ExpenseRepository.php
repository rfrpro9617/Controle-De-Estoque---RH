<?php

namespace App\Back\Repositories;

use App\Back\DTOs\ExpenseFilterDTO;
use App\Back\Models\ExpenseItem;
use App\Back\Models\ExpenseMaterial;
use Core\Database;
use DateTimeImmutable;

final class ExpenseRepository
{
  public function total(ExpenseFilterDTO $filters): float
  {
    [$conditions, $parameters] = $this->buildConditions($filters);
    $sql = 'SELECT COALESCE(SUM(gasto.valor_total), 0)
            FROM estoque_gastos gasto
              INNER JOIN estoque_materiais material ON material.id = gasto.material_id
            WHERE ' . implode(' AND ', $conditions);
    $statement = Database::connection()->prepare($sql);
    $statement->execute($parameters);

    return (float) $statement->fetchColumn();
  }

  /**
   * @return ExpenseItem[]
   */
  public function findItems(ExpenseFilterDTO $filters): array
  {
    [$conditions, $parameters] = $this->buildConditions($filters);
    $statement = Database::connection()->prepare(
      'SELECT
         material.id AS material_id,
         material.codigo AS material_codigo,
         material.nome AS material_nome,
         SUM(gasto.quantidade) AS quantidade,
         SUM(gasto.valor_total) AS valor_total
       FROM estoque_gastos gasto
         INNER JOIN estoque_materiais material ON material.id = gasto.material_id
       WHERE ' . implode(' AND ', $conditions) . '
       GROUP BY material.id, material.codigo, material.nome
       ORDER BY SUM(gasto.valor_total) DESC, material.nome ASC, material.id ASC'
    );
    $statement->execute($parameters);

    return array_map(
      static fn(array $row): ExpenseItem => new ExpenseItem(
        (int) $row['material_id'],
        $row['material_codigo'],
        $row['material_nome'],
        (int) $row['quantidade'],
        (float) $row['valor_total']
      ),
      $statement->fetchAll()
    );
  }

  /**
   * @return ExpenseMaterial[]
   */
  public function findMaterials(): array
  {
    $statement = Database::connection()->query(
      'SELECT id, codigo, nome
       FROM estoque_materiais
       ORDER BY nome ASC, id ASC'
    );

    return array_map(
      static fn(array $row): ExpenseMaterial => new ExpenseMaterial(
        (int) $row['id'],
        $row['codigo'],
        $row['nome']
      ),
      $statement->fetchAll()
    );
  }

  /**
   * @return array{0: string[], 1: array<string, int|string>}
   */
  private function buildConditions(ExpenseFilterDTO $filters): array
  {
    $monthStart = DateTimeImmutable::createFromFormat('!Y-m', $filters->month);
    if ($monthStart === false) {
      throw new \InvalidArgumentException('O mês do filtro de gastos é inválido.');
    }
    $nextMonth = $monthStart->modify('+1 month');

    $conditions = [
      'gasto.data >= :month_start',
      'gasto.data < :month_end',
    ];
    $parameters = [
      'month_start' => $monthStart->format('Y-m-d'),
      'month_end' => $nextMonth->format('Y-m-d'),
    ];

    if ($filters->categoryId !== null) {
      $conditions[] = 'material.categoria_id = :category_id';
      $parameters['category_id'] = $filters->categoryId;
    }

    if ($filters->materialId !== null) {
      $conditions[] = 'gasto.material_id = :material_id';
      $parameters['material_id'] = $filters->materialId;
    }

    return [$conditions, $parameters];
  }
}

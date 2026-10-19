<?php

namespace App\Back\Repositories;

use App\Back\Models\Sector;
use Core\Database;

final class SectorRepository
{
  /**
   * @return Sector[]
   */
  public function findActive(): array
  {
    $statement = Database::connection()->query(
      'SELECT id, nome, sigla
       FROM estoque_setores
       WHERE ativo = 1
       ORDER BY nome ASC, id ASC'
    );

    return array_map(
      static fn(array $row): Sector => new Sector(
        (int) $row['id'],
        $row['nome'],
        $row['sigla'],
        true
      ),
      $statement->fetchAll()
    );
  }

  /**
   * @return Sector[]
   */
  public function findAll(): array
  {
    $statement = Database::connection()->query(
      'SELECT id, nome, sigla, ativo
       FROM estoque_setores
       ORDER BY nome ASC, id ASC'
    );

    return array_map(
      static fn(array $row): Sector => new Sector(
        (int) $row['id'],
        $row['nome'],
        $row['sigla'],
        (int) $row['ativo'] === 1
      ),
      $statement->fetchAll()
    );
  }

  public function isActive(int $id): bool
  {
    $statement = Database::connection()->prepare(
      'SELECT id
       FROM estoque_setores
       WHERE id = :id AND ativo = 1
       FOR UPDATE'
    );
    $statement->execute(['id' => $id]);

    return $statement->fetchColumn() !== false;
  }
}

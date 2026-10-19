<?php

namespace App\Back\Repositories;

use App\Back\DTOs\PossessionFilterDTO;
use App\Back\Models\Possession;
use Core\Database;

final class PossessionRepository
{
  /**
   * @return Possession[]
   */
  public function findAll(PossessionFilterDTO $filters): array
  {
    $sql = 'SELECT
              setor.nome AS setor_nome,
              material.codigo AS material_codigo,
              material.nome AS material_nome,
              COALESCE(categoria.nome, \'Sem categoria\') AS categoria_nome,
              posse.quantidade,
              posse.ultima_saida
            FROM estoque_posses posse
              INNER JOIN estoque_setores setor ON setor.id = posse.setor_id
              INNER JOIN estoque_materiais material ON material.id = posse.material_id
              LEFT JOIN estoque_categorias categoria ON categoria.id = material.categoria_id';
    $conditions = ['posse.quantidade > 0'];
    $parameters = [];

    if ($filters->search !== '') {
      $conditions[] = '(material.codigo LIKE :search_code OR material.nome LIKE :search_name OR categoria.nome LIKE :search_category OR setor.nome LIKE :search_sector)';
      $search = '%' . $filters->search . '%';
      $parameters['search_code'] = $search;
      $parameters['search_name'] = $search;
      $parameters['search_category'] = $search;
      $parameters['search_sector'] = $search;
    }

    if ($filters->sectorId !== null) {
      $conditions[] = 'posse.setor_id = :sector_id';
      $parameters['sector_id'] = $filters->sectorId;
    }

    if ($filters->categoryId !== null) {
      $conditions[] = 'material.categoria_id = :category_id';
      $parameters['category_id'] = $filters->categoryId;
    }

    $sql .= ' WHERE ' . implode(' AND ', $conditions)
      . ' ORDER BY setor.nome ASC, material.nome ASC, material.id ASC';
    $statement = Database::connection()->prepare($sql);
    $statement->execute($parameters);

    return array_map(
      static fn(array $row): Possession => new Possession(
        $row['setor_nome'],
        $row['material_codigo'],
        $row['material_nome'],
        $row['categoria_nome'],
        (int) $row['quantidade'],
        $row['ultima_saida']
      ),
      $statement->fetchAll()
    );
  }
}

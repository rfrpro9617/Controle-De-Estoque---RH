<?php

namespace App\Back\Repositories;

use App\Back\Models\PurchaseOrder;
use Core\Database;

final class PurchaseOrderRepository
{
  /**
   * @return PurchaseOrder[]
   */
  public function findAll(string $search = '', string $situation = ''): array
  {
    $sql = 'SELECT pedido.id, pedido.numero, pedido.data, pedido.fornecedor_id,
                   fornecedor.nome AS fornecedor_nome, pedido.situacao, pedido.solicitante_id
            FROM estoque_pedidos pedido
            INNER JOIN estoque_fornecedores fornecedor ON fornecedor.id = pedido.fornecedor_id';
    $conditions = [];
    $parameters = [];

    if ($search !== '') {
      $conditions[] = '(pedido.numero LIKE :search_number OR fornecedor.nome LIKE :search_supplier)';
      $parameters['search_number'] = '%' . $search . '%';
      $parameters['search_supplier'] = '%' . $search . '%';
    }

    if ($situation !== '') {
      $conditions[] = 'pedido.situacao = :situation';
      $parameters['situation'] = $situation;
    }

    if ($conditions !== []) {
      $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY pedido.data DESC, pedido.id DESC';
    $stmt = Database::connection()->prepare($sql);
    $stmt->execute($parameters);

    return array_map(
      fn(array $row) => $this->mapRow($row),
      $stmt->fetchAll()
    );
  }

  public function findById(int $id): ?PurchaseOrder
  {
    $stmt = Database::connection()->prepare(
      'SELECT pedido.id, pedido.numero, pedido.data, pedido.fornecedor_id,
              fornecedor.nome AS fornecedor_nome, pedido.situacao
       FROM estoque_pedidos pedido
       INNER JOIN estoque_fornecedores fornecedor ON fornecedor.id = pedido.fornecedor_id
       WHERE pedido.id = :id'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row === false ? null : $this->mapRow($row);
  }

  private function mapRow(array $row): PurchaseOrder
  {
    return new PurchaseOrder(
      (int) $row['id'],
      $row['numero'],
      $row['data'],
      (int) $row['fornecedor_id'],
      $row['fornecedor_nome'],
      $row['situacao'],
      $row['solicitante_id'] === null ? null : (int) $row['solicitante_id']
    );
  }
}

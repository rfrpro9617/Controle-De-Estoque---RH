<?php

namespace App\Back\Services;

use App\Back\Models\PurchaseOrder;
use App\Back\Repositories\PurchaseOrderRepository;

final class PurchaseOrderService
{
  public function __construct(
    private PurchaseOrderRepository $purchaseOrderRepository = new PurchaseOrderRepository()
  ) {}

  public function findAll(string $search = '', string $situation = ''): array
  {
    return $this->purchaseOrderRepository->findAll($search, $situation);
  }

  public function findById(int $id): ?PurchaseOrder
  {
    return $this->purchaseOrderRepository->findById($id);
  }

}

<?php

namespace App\Back\Services;

use App\Back\Repositories\SupplierRepository;

final class SupplierService
{
  public function __construct(
    private SupplierRepository $supplierRepository = new SupplierRepository()
  ) {}

  public function findAll(): array
  {
    return $this->supplierRepository->findAll();
  }
}

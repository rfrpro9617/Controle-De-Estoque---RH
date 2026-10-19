<?php

namespace App\Back\Services;

use App\Back\DTOs\ExpenseFilterDTO;
use App\Back\Repositories\ExpenseRepository;

final class ExpenseService
{
  public function __construct(
    private ExpenseRepository $expenseRepository = new ExpenseRepository()
  ) {}

  public function total(ExpenseFilterDTO $filters): float
  {
    return $this->expenseRepository->total($filters);
  }

  public function findItems(ExpenseFilterDTO $filters): array
  {
    return $this->expenseRepository->findItems($filters);
  }

  public function findMaterials(): array
  {
    return $this->expenseRepository->findMaterials();
  }
}

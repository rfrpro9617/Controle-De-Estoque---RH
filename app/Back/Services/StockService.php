<?php

namespace App\Back\Services;

use App\Back\DTOs\StockFilterDTO;
use App\Back\Repositories\StockRepository;

final class StockService
{
  public function __construct(
    private StockRepository $stockRepository = new StockRepository()
  ) {}

  public function findAll(StockFilterDTO $filters): array
  {
    return $this->stockRepository->findAll($filters);
  }

  public function findActiveForExit(): array
  {
    return $this->stockRepository->findActiveForExit();
  }
}

<?php

namespace App\Back\Services;

use App\Back\DTOs\StockExitDTO;
use App\Back\Repositories\StockMovementRepository;

final class StockExitService
{
  public function __construct(
    private StockMovementRepository $stockMovementRepository = new StockMovementRepository()
  ) {}

  public function register(StockExitDTO $exit): bool
  {
    return $this->stockMovementRepository->registerExit($exit);
  }
}

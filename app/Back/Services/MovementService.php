<?php

namespace App\Back\Services;

use App\Back\DTOs\MovementFilterDTO;
use App\Back\Repositories\MovementRepository;

final class MovementService
{
  public function __construct(
    private MovementRepository $movementRepository = new MovementRepository()
  ) {}

  public function findAll(MovementFilterDTO $filters): array
  {
    return $this->movementRepository->findAll($filters);
  }
}

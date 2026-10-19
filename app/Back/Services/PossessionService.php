<?php

namespace App\Back\Services;

use App\Back\DTOs\PossessionFilterDTO;
use App\Back\Repositories\PossessionRepository;

final class PossessionService
{
  public function __construct(
    private PossessionRepository $possessionRepository = new PossessionRepository()
  ) {}

  public function findAll(PossessionFilterDTO $filters): array
  {
    return $this->possessionRepository->findAll($filters);
  }
}

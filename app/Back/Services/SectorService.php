<?php

namespace App\Back\Services;

use App\Back\Repositories\SectorRepository;

final class SectorService
{
  public function __construct(
    private SectorRepository $sectorRepository = new SectorRepository()
  ) {}

  public function findActive(): array
  {
    return $this->sectorRepository->findActive();
  }

  public function findAll(): array
  {
    return $this->sectorRepository->findAll();
  }
}

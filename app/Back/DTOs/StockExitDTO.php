<?php

namespace App\Back\DTOs;

final class StockExitDTO
{
  public function __construct(
    public readonly int $materialId,
    public readonly int $quantity,
    public readonly int $sectorId,
    public readonly string $date,
    public readonly ?string $observation,
  ) {}
}

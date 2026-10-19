<?php

namespace App\Back\Models;

final class Possession
{
  public function __construct(
    public readonly string $sectorName,
    public readonly string $materialCode,
    public readonly string $materialName,
    public readonly string $categoryName,
    public readonly int $quantity,
    public readonly ?string $lastExit,
  ) {}
}

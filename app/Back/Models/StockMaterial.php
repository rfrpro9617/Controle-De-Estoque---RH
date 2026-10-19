<?php

namespace App\Back\Models;

use App\Back\Utils\StockStatus;

final class StockMaterial
{
  public function __construct(
    public readonly int $id,
    public readonly string $code,
    public readonly string $name,
    public readonly string $categoryName,
    public readonly int $currentStock,
    public readonly int $minimumStock,
    public readonly bool $active,
  ) {}

  public function status(): string
  {
    return StockStatus::fromStock($this->currentStock, $this->minimumStock, $this->active);
  }
}

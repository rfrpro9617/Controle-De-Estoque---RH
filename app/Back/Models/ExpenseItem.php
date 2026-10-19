<?php

namespace App\Back\Models;

final class ExpenseItem
{
  public function __construct(
    public readonly int $materialId,
    public readonly string $materialCode,
    public readonly string $materialName,
    public readonly int $quantity,
    public readonly float $total,
  ) {}
}

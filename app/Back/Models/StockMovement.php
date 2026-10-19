<?php

namespace App\Back\Models;

final class StockMovement
{
  public function __construct(
    public readonly int $id,
    public readonly string $date,
    public readonly string $materialCode,
    public readonly string $materialName,
    public readonly string $categoryName,
    public readonly string $type,
    public readonly int $quantity,
    public readonly ?string $sectorName,
    public readonly ?string $observation,
    public readonly ?string $orderNumber,
  ) {}

  public function typeLabel(): string
  {
    return $this->type === 'entrada' ? 'Entrada' : 'Saída';
  }
}

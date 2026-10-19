<?php

namespace App\Back\DTOs;

final class StockFilterDTO
{
  public function __construct(
    public readonly string $search = '',
    public readonly ?int $categoryId = null,
    public readonly string $status = '',
    public readonly bool $hasInvalidInput = false,
  ) {}
}

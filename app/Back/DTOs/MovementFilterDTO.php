<?php

namespace App\Back\DTOs;

final class MovementFilterDTO
{
  public function __construct(
    public readonly string $search = '',
    public readonly string $type = '',
    public readonly ?int $sectorId = null,
    public readonly ?int $categoryId = null,
    public readonly string $dateFrom = '',
    public readonly string $dateTo = '',
    public readonly bool $hasInvalidInput = false,
  ) {}
}

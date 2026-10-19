<?php

namespace App\Back\DTOs;

final class PossessionFilterDTO
{
  public function __construct(
    public readonly string $search = '',
    public readonly ?int $sectorId = null,
    public readonly ?int $categoryId = null,
    public readonly bool $hasInvalidInput = false,
  ) {}
}

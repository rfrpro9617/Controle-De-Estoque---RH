<?php

namespace App\Back\DTOs;

final class ExpenseFilterDTO
{
  public function __construct(
    public readonly string $month,
    public readonly ?int $categoryId = null,
    public readonly ?int $materialId = null,
    public readonly bool $hasInvalidInput = false,
  ) {}
}

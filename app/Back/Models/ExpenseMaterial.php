<?php

namespace App\Back\Models;

final class ExpenseMaterial
{
  public function __construct(
    public readonly int $id,
    public readonly string $code,
    public readonly string $name,
  ) {}
}

<?php

namespace App\Back\Models;

final class Category
{
  public function __construct(
    public readonly int $id,
    public readonly string $name,
    public readonly ?string $description,
    public readonly bool $active,
    public readonly int $materialCount,
  ) {}
}

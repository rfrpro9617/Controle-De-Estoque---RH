<?php

namespace App\Back\Models;

final class Supplier
{
  public function __construct(
    public readonly int $id,
    public readonly string $name,
    public readonly bool $active,
  ) {}
}

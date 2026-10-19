<?php

namespace App\Back\Models;

final class Sector
{
  public function __construct(
    public readonly int $id,
    public readonly string $name,
    public readonly string $acronym,
    public readonly bool $active = true,
  ) {}
}

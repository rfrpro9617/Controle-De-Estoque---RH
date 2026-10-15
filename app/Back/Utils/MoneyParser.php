<?php

namespace App\Back\Utils;

final class MoneyParser
{
  public static function parseMoney(string $value): float
  {
    $clean = str_replace(['.', ','], ['', '.'], $value);
    return (float)$clean;
  }
}

<?php

namespace App\Back\Utils;

final class RegexCapture
{
  public static function firstGroup(array $matches): ?string
  {
    $value = trim((string) ($matches[1] ?? ''));
    return $value === '' ? null : $value;
  }
}

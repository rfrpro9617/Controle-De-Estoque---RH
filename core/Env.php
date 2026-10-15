<?php

namespace Core;

final class Env
{
  public static function load(string $path): void
  {
    if (!is_file($path) || !is_readable($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) return;

    foreach ($lines as $line) {
      $line = trim($line);
      if ($line === '' || $line[0] === '#') continue;
      if (strpos($line, 'export ') === 0) $line = substr($line, 7);

      $separator = strpos($line, '=');
      if ($separator === false) continue;

      $key = trim(substr($line, 0, $separator));
      if (!preg_match('/^DB_[A-Z0-9_]+$/', $key)) continue;

      $value = trim(substr($line, $separator + 1));
      if (strlen($value) >= 2) {
        $quote = $value[0];
        if (($quote === '"' || $quote === "'") && substr($value, -1) === $quote) {
          $value = substr($value, 1, -1);
          if ($quote === '"') $value = stripcslashes($value);
        }
      }

      putenv($key . '=' . $value);
      $_ENV[$key] = $value;
      $_SERVER[$key] = $value;
    }
  }
}

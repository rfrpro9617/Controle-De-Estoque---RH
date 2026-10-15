<?php

final class Autoloader
{
  public static function register(): void
  {
    spl_autoload_register(function (string $class): void {
      $prefixes = [
        'App\\' => __DIR__ . '/../app/',
        'Core\\' => __DIR__ . '/',
      ];

      foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
          continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

        if (is_file($file)) {
          require_once $file;
        }

        return;
      }
    });
  }
}

<?php

namespace Core;

use PDO;

final class Database
{
  private static ?PDO $connection = null;

  public static function connection(): PDO
  {
    if (self::$connection instanceof PDO) return self::$connection;

    $configFile = __DIR__ . '/../config/database.php';
    $config = is_file($configFile) ? require $configFile : [];
    $environmentKeys = [
      'host' => 'DB_HOST',
      'database' => 'DB_DATABASE',
      'username' => 'DB_USERNAME',
      'password' => 'DB_PASSWORD',
      'charset' => 'DB_CHARSET',
    ];
    foreach ($environmentKeys as $key => $environmentKey) {
      $value = getenv($environmentKey);
      if ($value !== false) $config[$key] = $value;
    }

    foreach (['host', 'database', 'username'] as $key) {
      if (empty($config[$key])) {
        // TODO: validar como a interface trata a ausência de uma configuração de banco de dados.
        throw new \RuntimeException("Configuração do banco ausente: {$key}");
      }
    }

    $charset = $config['charset'] ?? 'utf8mb4';
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $charset);
    self::$connection = new PDO($dsn, $config['username'], $config['password'] ?? '', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return self::$connection;
  }
}

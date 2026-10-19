<?php

namespace App\Back\Utils;

final class StockStatus
{
  public const AVAILABLE = 'disponivel';
  public const LOW = 'estoque_baixo';
  public const EMPTY = 'sem_estoque';
  public const INACTIVE = 'inativo';

  public static function labels(): array
  {
    return [
      self::AVAILABLE => 'Disponível',
      self::LOW => 'Estoque baixo',
      self::EMPTY => 'Sem estoque',
      self::INACTIVE => 'Inativo',
    ];
  }

  public static function fromStock(int $currentStock, int $minimumStock, bool $active): string
  {
    if (!$active) {
      return self::INACTIVE;
    }

    if ($currentStock === 0) {
      return self::EMPTY;
    }

    return $currentStock <= $minimumStock ? self::LOW : self::AVAILABLE;
  }

  public static function sqlExpression(): string
  {
    return 'CASE
      WHEN material.ativo = 0 THEN \'' . self::INACTIVE . '\'
      WHEN material.estoque_central = 0 THEN \'' . self::EMPTY . '\'
      WHEN material.estoque_central <= material.estoque_minimo THEN \'' . self::LOW . '\'
      ELSE \'' . self::AVAILABLE . '\'
    END';
  }
}

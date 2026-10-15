<?php

namespace App\Back\Models;

final class PurchaseOrder
{
  public function __construct(
    public readonly int $id,
    public readonly string $number,
    public readonly string $date,
    public readonly int $supplierId,
    public readonly string $supplierName,
    public readonly string $situation,
    public readonly ?int $requesterId,
  ) {}

  public function situationLabel(): string
  {
    return match ($this->situation) {
      'em_aprovacao' => 'Em aprovação',
      'aprovado' => 'Aprovado',
      'recebido' => 'Recebido',
      'parcialmente_recebido' => 'Parcialmente recebido',
      'cancelado' => 'Cancelado',
      default => ucfirst(str_replace('_', ' ', $this->situation)),
    };
  }
}

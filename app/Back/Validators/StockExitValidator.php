<?php

namespace App\Back\Validators;

use App\Back\DTOs\StockExitDTO;
use DateTimeImmutable;

final class StockExitValidator
{
  /**
   * @return array{exit: ?StockExitDTO, error: ?string}
   */
  public function validate(array $input): array
  {
    $materialId = $this->positiveInteger($input['material_id'] ?? null);
    $quantity = $this->positiveInteger($input['quantidade'] ?? null);
    $sectorId = $this->positiveInteger($input['setor_id'] ?? null);

    if ($materialId === null || $quantity === null || $sectorId === null) {
      return [
        'exit' => null,
        'error' => 'Selecione um material e um setor, e informe uma quantidade maior que zero.',
      ];
    }

    $date = $input['data'] ?? date('Y-m-d');
    if (
      !is_string($date)
      || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
      || !self::isValidDate($date)
    ) {
      return ['exit' => null, 'error' => 'Informe uma data válida para a saída.'];
    }

    $observation = $input['observacao'] ?? '';
    if (!is_string($observation)) {
      return ['exit' => null, 'error' => 'A observação informada é inválida.'];
    }

    $observation = trim($observation);
    if (mb_strlen($observation, 'UTF-8') > 65535) {
      return ['exit' => null, 'error' => 'A observação excede o tamanho permitido.'];
    }

    return [
      'exit' => new StockExitDTO(
        $materialId,
        $quantity,
        $sectorId,
        $date,
        $observation === '' ? null : $observation
      ),
      'error' => null,
    ];
  }

  private function positiveInteger(mixed $value): ?int
  {
    if (!is_string($value) && !is_int($value)) {
      return null;
    }

    $integer = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    return $integer === false || $integer > 4294967295 ? null : $integer;
  }

  private static function isValidDate(string $date): bool
  {
    $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    $errors = DateTimeImmutable::getLastErrors();

    return $parsedDate !== false
      && $parsedDate->format('Y-m-d') === $date
      && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
  }
}

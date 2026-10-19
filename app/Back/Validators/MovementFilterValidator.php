<?php

namespace App\Back\Validators;

use App\Back\DTOs\MovementFilterDTO;
use DateTimeImmutable;

final class MovementFilterValidator
{
  private const TYPES = ['entrada', 'saida'];

  public function validate(array $input): MovementFilterDTO
  {
    $invalidInput = false;
    $search = $input['busca'] ?? '';
    if (!is_string($search)) {
      $search = '';
      $invalidInput = true;
    } else {
      $search = trim($search);
    }

    $type = $input['tipo'] ?? '';
    if (!is_string($type) || ($type !== '' && !in_array($type, self::TYPES, true))) {
      $type = '';
      $invalidInput = true;
    }

    $sectorId = $this->parseOptionalId($input['setor'] ?? '', $invalidInput);
    $categoryId = $this->parseOptionalId($input['categoria'] ?? '', $invalidInput);
    $dateFrom = $this->parseDate($input['de'] ?? '', $invalidInput);
    $dateTo = $this->parseDate($input['ate'] ?? '', $invalidInput);

    if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
      $dateFrom = '';
      $dateTo = '';
      $invalidInput = true;
    }

    return new MovementFilterDTO(
      $search,
      $type,
      $sectorId,
      $categoryId,
      $dateFrom,
      $dateTo,
      $invalidInput
    );
  }

  private function parseOptionalId(mixed $value, bool &$invalidInput): ?int
  {
    if ($value === '') {
      return null;
    }

    $id = is_string($value)
      ? filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
      : false;
    if ($id === false) {
      $invalidInput = true;
      return null;
    }

    return $id;
  }

  private function parseDate(mixed $value, bool &$invalidInput): string
  {
    if ($value === '') {
      return '';
    }

    if (
      !is_string($value)
      || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)
      || !self::isValidDate($value)
    ) {
      $invalidInput = true;
      return '';
    }

    return $value;
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

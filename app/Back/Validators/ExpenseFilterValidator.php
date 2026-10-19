<?php

namespace App\Back\Validators;

use App\Back\DTOs\ExpenseFilterDTO;
use DateTimeImmutable;

final class ExpenseFilterValidator
{
  public function validate(array $input): ExpenseFilterDTO
  {
    $invalidInput = false;
    $month = $input['mes'] ?? date('Y-m');
    if (!is_string($month) || !self::isValidMonth($month)) {
      $month = date('Y-m');
      $invalidInput = true;
    }

    $categoryId = $this->parseOptionalId($input['categoria'] ?? '', $invalidInput);
    $materialId = $this->parseOptionalId($input['material'] ?? '', $invalidInput);

    return new ExpenseFilterDTO($month, $categoryId, $materialId, $invalidInput);
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

  private static function isValidMonth(string $month): bool
  {
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
      return false;
    }

    $date = DateTimeImmutable::createFromFormat('!Y-m', $month);
    $errors = DateTimeImmutable::getLastErrors();

    return $date !== false
      && $date->format('Y-m') === $month
      && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
  }
}

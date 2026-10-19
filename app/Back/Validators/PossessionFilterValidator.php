<?php

namespace App\Back\Validators;

use App\Back\DTOs\PossessionFilterDTO;

final class PossessionFilterValidator
{
  public function validate(array $input): PossessionFilterDTO
  {
    $invalidInput = false;
    $search = $input['busca'] ?? '';
    if (!is_string($search)) {
      $search = '';
      $invalidInput = true;
    } else {
      $search = trim($search);
    }

    $sectorId = $this->parseOptionalId($input['setor'] ?? '', $invalidInput);
    $categoryId = $this->parseOptionalId($input['categoria'] ?? '', $invalidInput);

    return new PossessionFilterDTO($search, $sectorId, $categoryId, $invalidInput);
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
}

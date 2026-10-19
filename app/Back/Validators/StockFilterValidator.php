<?php

namespace App\Back\Validators;

use App\Back\DTOs\StockFilterDTO;
use App\Back\Utils\StockStatus;

final class StockFilterValidator
{
  public function validate(array $input): StockFilterDTO
  {
    $invalidInput = false;
    $search = $input['busca'] ?? '';
    if (!is_string($search)) {
      $search = '';
      $invalidInput = true;
    } else {
      $search = trim($search);
    }

    $rawCategoryId = $input['categoria'] ?? '';
    $categoryId = null;
    if ($rawCategoryId !== '') {
      $validatedCategoryId = is_string($rawCategoryId)
        ? filter_var($rawCategoryId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
        : false;

      if ($validatedCategoryId === false) {
        $invalidInput = true;
      } else {
        $categoryId = $validatedCategoryId;
      }
    }

    $status = $input['status'] ?? '';
    if (!is_string($status) || ($status !== '' && !array_key_exists($status, StockStatus::labels()))) {
      $status = '';
      $invalidInput = true;
    }

    return new StockFilterDTO($search, $categoryId, $status, $invalidInput);
  }
}

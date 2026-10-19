<?php

namespace App\Back\Services;

use App\Back\Repositories\CategoryRepository;

final class CategoryService
{
  public function __construct(
    private CategoryRepository $categoryRepository = new CategoryRepository()
  ) {}

  public function findAll(): array
  {
    return $this->categoryRepository->findAll();
  }
}

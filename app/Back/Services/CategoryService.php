<?php

namespace App\Back\Services;

use App\Back\Models\Category;
use App\Back\Repositories\CategoryRepository;

final class CategoryService
{
  public function __construct(
    private CategoryRepository $categoryRepository = new CategoryRepository()
  ) {}

  /**
   * @return Category[]
   */
  public function findAll(): array
  {
    return $this->categoryRepository->findAll();
  }
}

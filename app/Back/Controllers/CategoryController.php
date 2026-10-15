<?php

namespace App\Back\Controllers;

use App\Back\Services\CategoryService;
use App\Back\Utils\Navigation;
use Core\Controller;

final class CategoryController extends Controller
{
  public function __construct(
    private CategoryService $categoryService = new CategoryService()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'categorias');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $this->view(
      'stock/categories',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'categorias',
        'navigationGroups' => $navigationGroups,
        'section' => $selectedSection,
        'categories' => $this->categoryService->findAll(),
      ],
      'inventory'
    );
  }
}

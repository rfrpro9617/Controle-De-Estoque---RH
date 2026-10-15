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

    foreach ($navigationGroups as $items) {
      foreach ($items as $item) {
        if ($item['slug'] === 'categorias') {
          $selectedSection = $item;
        }
      }
    }

    if (!isset($selectedSection)) {
      http_response_code(404);
      echo '404 - Página não encontrada';
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

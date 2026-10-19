<?php

namespace App\Back\Controllers;

use App\Back\Services\CategoryService;
use App\Back\Services\ExpenseService;
use App\Back\Utils\Navigation;
use App\Back\Validators\ExpenseFilterValidator;
use Core\Controller;

final class ExpenseController extends Controller
{
  public function __construct(
    private ExpenseService $expenseService = new ExpenseService(),
    private CategoryService $categoryService = new CategoryService(),
    private ExpenseFilterValidator $filterValidator = new ExpenseFilterValidator()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'gastos');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $filters = $this->filterValidator->validate($_GET);
    $this->view(
      'stock/expenses',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'gastos',
        'navigationGroups' => $navigationGroups,
        'filters' => $filters,
        'total' => $this->expenseService->total($filters),
        'items' => $this->expenseService->findItems($filters),
        'materials' => $this->expenseService->findMaterials(),
        'categories' => $this->categoryService->findAll(),
      ],
      'inventory'
    );
  }
}

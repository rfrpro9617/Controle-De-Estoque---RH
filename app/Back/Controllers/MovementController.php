<?php

namespace App\Back\Controllers;

use App\Back\Services\CategoryService;
use App\Back\Services\MovementService;
use App\Back\Services\SectorService;
use App\Back\Utils\Navigation;
use App\Back\Validators\MovementFilterValidator;
use Core\Controller;

final class MovementController extends Controller
{
  public function __construct(
    private MovementService $movementService = new MovementService(),
    private SectorService $sectorService = new SectorService(),
    private CategoryService $categoryService = new CategoryService(),
    private MovementFilterValidator $filterValidator = new MovementFilterValidator()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'movimentacoes');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $filters = $this->filterValidator->validate($_GET);
    $this->view(
      'stock/movements',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'movimentacoes',
        'navigationGroups' => $navigationGroups,
        'movements' => $this->movementService->findAll($filters),
        'sectors' => $this->sectorService->findAll(),
        'categories' => $this->categoryService->findAll(),
        'filters' => $filters,
        'types' => ['entrada' => 'Entrada', 'saida' => 'Saída'],
      ],
      'inventory'
    );
  }
}

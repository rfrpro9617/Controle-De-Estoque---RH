<?php

namespace App\Back\Controllers;

use App\Back\Services\CategoryService;
use App\Back\Services\PossessionService;
use App\Back\Services\SectorService;
use App\Back\Utils\Navigation;
use App\Back\Validators\PossessionFilterValidator;
use Core\Controller;

final class PossessionController extends Controller
{
  public function __construct(
    private PossessionService $possessionService = new PossessionService(),
    private SectorService $sectorService = new SectorService(),
    private CategoryService $categoryService = new CategoryService(),
    private PossessionFilterValidator $filterValidator = new PossessionFilterValidator()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'distribuido-aos-setores');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $filters = $this->filterValidator->validate($_GET);
    $this->view(
      'stock/possessions',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'distribuido-aos-setores',
        'navigationGroups' => $navigationGroups,
        'possessions' => $this->possessionService->findAll($filters),
        'sectors' => $this->sectorService->findAll(),
        'categories' => $this->categoryService->findAll(),
        'filters' => $filters,
      ],
      'inventory'
    );
  }
}

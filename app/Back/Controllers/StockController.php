<?php

namespace App\Back\Controllers;

use App\Back\Services\CategoryService;
use App\Back\Services\SectorService;
use App\Back\Services\StockExitService;
use App\Back\Services\StockService;
use App\Back\Utils\Navigation;
use App\Back\Utils\StockStatus;
use App\Back\Validators\StockExitValidator;
use App\Back\Validators\StockFilterValidator;
use Core\Csrf;
use Core\Controller;

final class StockController extends Controller
{
  public function __construct(
    private StockService $stockService = new StockService(),
    private CategoryService $categoryService = new CategoryService(),
    private SectorService $sectorService = new SectorService(),
    private StockExitService $stockExitService = new StockExitService(),
    private StockFilterValidator $stockFilterValidator = new StockFilterValidator(),
    private StockExitValidator $stockExitValidator = new StockExitValidator()
  ) {}

  public function index(): void
  {
    $filters = $this->stockFilterValidator->validate($_GET);
    $feedback = ($_GET['saida'] ?? null) === 'registrada' ? 'Saída registrada com sucesso.' : null;
    $this->renderInventory($filters, $feedback, []);
  }

  public function registerExit(): void
  {
    if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
      http_response_code(403);
      $this->renderInventory(
        $this->stockFilterValidator->validate([]),
        'Sua sessão expirou. Atualize a página e tente novamente.',
        $_POST
      );
      return;
    }

    $validation = $this->stockExitValidator->validate($_POST);
    if ($validation['exit'] === null) {
      $this->renderInventory(
        $this->stockFilterValidator->validate([]),
        $validation['error'],
        $_POST
      );
      return;
    }

    if (!$this->stockExitService->register($validation['exit'])) {
      $this->renderInventory(
        $this->stockFilterValidator->validate([]),
        'Não foi possível registrar a saída. Confira se o setor está ativo e se o material possui quantidade suficiente no estoque.',
        $_POST
      );
      return;
    }

    header('Location: ' . BASE_URL . '/estoque/estoque?saida=registrada');
    exit;
  }

  private function renderInventory(
    \App\Back\DTOs\StockFilterDTO $filters,
    ?string $feedback,
    array $exitForm
  ): void {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'estoque');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $this->view(
      'stock/inventory',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'estoque',
        'navigationGroups' => $navigationGroups,
        'materials' => $this->stockService->findAll($filters),
        'categories' => $this->categoryService->findAll(),
        'exitMaterials' => $this->stockService->findActiveForExit(),
        'sectors' => $this->sectorService->findActive(),
        'filters' => $filters,
        'statuses' => StockStatus::labels(),
        'csrfToken' => Csrf::token(),
        'feedback' => $feedback,
        'exitForm' => $exitForm,
      ],
      'inventory'
    );
  }
}

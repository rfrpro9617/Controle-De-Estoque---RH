<?php

namespace App\Back\Controllers;

use App\Back\Services\SupplierService;
use App\Back\Utils\Navigation;
use Core\Controller;

final class SupplierController extends Controller
{
  public function __construct(
    private SupplierService $supplierService = new SupplierService()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');

    foreach ($navigationGroups as $items) {
      foreach ($items as $item) {
        if ($item['slug'] === 'fornecedores') {
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
      'stock/suppliers',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'fornecedores',
        'navigationGroups' => $navigationGroups,
        'section' => $selectedSection,
        'suppliers' => $this->supplierService->findAll(),
      ],
      'inventory'
    );
  }
}

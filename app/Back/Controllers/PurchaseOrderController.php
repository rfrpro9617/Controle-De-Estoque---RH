<?php

namespace App\Back\Controllers;

use App\Back\Services\PurchaseOrderService;
use App\Back\Utils\Navigation;
use Core\Controller;

final class PurchaseOrderController extends Controller
{
  private const SITUATIONS = [
    'em_aprovacao' => 'Em aprovação',
    'aprovado' => 'Aprovado',
    'recebido' => 'Recebido',
    'parcialmente_recebido' => 'Parcialmente recebido',
    'cancelado' => 'Cancelado',
  ];

  public function __construct(
    private PurchaseOrderService $purchaseOrderService = new PurchaseOrderService()
  ) {}

  public function index(): void
  {
    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'pedidos');

    if ($selectedSection === null) {
      $this->notFound();
      return;
    }

    $search = is_string($_GET['busca'] ?? null) ? trim($_GET['busca']) : '';
    $requestedSituation = $_GET['situacao'] ?? '';
    $invalidSituation = !is_string($requestedSituation)
      || ($requestedSituation !== '' && !array_key_exists($requestedSituation, self::SITUATIONS));
    $situation = !$invalidSituation ? $requestedSituation : '';

    $this->view(
      'stock/orders',
      [
        'title' => $selectedSection['label'] . ' | Sistema de Estoque',
        'activeSection' => 'pedidos',
        'navigationGroups' => $navigationGroups,
        'section' => $selectedSection,
        'orders' => $this->purchaseOrderService->findAll($search, $situation),
        'search' => $search,
        'selectedSituation' => $situation,
        'situations' => self::SITUATIONS,
        'invalidSituation' => $invalidSituation,
      ],
      'inventory'
    );
  }

  public function show(string $id): void
  {
    $orderId = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($orderId === false) {
      $this->notFound();
      return;
    }

    $navigationGroups = Navigation::groups(BASE_URL . '/estoque');
    $selectedSection = Navigation::findBySlug($navigationGroups, 'pedidos');
    $order = $this->purchaseOrderService->findById($orderId);

    if ($selectedSection === null || $order === null) {
      $this->notFound();
      return;
    }

    $this->view(
      'stock/order-details',
      [
        'title' => 'Pedido ' . $order->number . ' | Sistema de Estoque',
        'activeSection' => 'pedidos',
        'navigationGroups' => $navigationGroups,
        'section' => $selectedSection,
        'order' => $order,
      ],
      'inventory'
    );
  }
}

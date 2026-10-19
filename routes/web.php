<?php

use Core\Router;
use Core\AuthGuard;

use App\Back\Controllers\AuthController;
use App\Back\Controllers\CategoryController;
use App\Back\Controllers\PurchaseOrderController;
use App\Back\Controllers\PdfTextExtractorController;
use App\Back\Controllers\SupplierController;
use App\Back\Controllers\StockController;
use App\Back\Controllers\PossessionController;
use App\Back\Controllers\MovementController;
use App\Back\Controllers\ExpenseController;

$router = new Router();

// Publics
$router->get('/', [AuthController::class, 'index']);
$router->get('/login', [AuthController::class, 'index']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/cadastro', [AuthController::class, 'registrationForm']);
$router->post('/cadastro', [AuthController::class, 'register']);

// Protected
$router->post(
  '/logout',
  [AuthController::class, 'logout'],
  [AuthGuard::class]
);

$router->get(
  '/estoque',
  [PdfTextExtractorController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/estoque',
  [StockController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/distribuido-aos-setores',
  [PossessionController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/movimentacoes',
  [MovementController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/gastos',
  [ExpenseController::class, 'index'],
  [AuthGuard::class]
);

$router->post(
  '/estoque/estoque/saida',
  [StockController::class, 'registerExit'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/importar-pdf',
  [PdfTextExtractorController::class, 'importPdf'],
  [AuthGuard::class]
);

$router->post(
  '/estoque/importar-pdf',
  [PdfTextExtractorController::class, 'uploadPdf'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/fornecedores',
  [SupplierController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/pedidos',
  [PurchaseOrderController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/pedidos/{id}',
  [PurchaseOrderController::class, 'show'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/categorias',
  [CategoryController::class, 'index'],
  [AuthGuard::class]
);

$router->get(
  '/estoque/{section}',
  [PdfTextExtractorController::class, 'index'],
  [AuthGuard::class]
);

$router->dispatch(
  $_SERVER['REQUEST_METHOD'],
  $_SERVER['REQUEST_URI']
);

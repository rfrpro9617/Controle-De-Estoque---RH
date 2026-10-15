<?php

use Core\Router;
use Core\AuthGuard;

use App\Back\Controllers\AuthController;
use App\Back\Controllers\PdfTextExtractorController;

$router = new Router();

// Publics
$router->get('/', [AuthController::class, 'index']);
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
  '/estoque/{section}',
  [PdfTextExtractorController::class, 'index'],
  [AuthGuard::class]
);

$router->dispatch(
  $_SERVER['REQUEST_METHOD'],
  $_SERVER['REQUEST_URI']
);

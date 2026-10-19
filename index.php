<?php

use App\Back\Repositories\UserRepository;
use Core\Auth;

require_once __DIR__ . '/core/Autoloader.php';
Autoloader::register();
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config/app.php';
Core\Env::load(BASE_PATH . '/.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_set_cookie_params([
    // Impede que o cookie de sessão seja acessado por scripts do lado do cliente
    'httponly' => true,
    // Garante que o navegador só envie o cookie de sessão de volta ao servidor através de conexões criptografadas (HTTPS)
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
  ]);
  session_start();
}

Auth::setUserProvider(new UserRepository());

require_once __DIR__ . '/routes/web.php';

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
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
  ]);
  session_start();
}

Auth::setUserProvider(new UserRepository());

require_once __DIR__ . '/routes/web.php';

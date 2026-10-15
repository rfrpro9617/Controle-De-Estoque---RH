<?php

define('APP_NAME', 'INTRANET');
define('BASE_PATH', dirname(__DIR__));
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$baseUrl = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
define('BASE_URL', $baseUrl === '' ? '' : '/' . ltrim($baseUrl, '/'));

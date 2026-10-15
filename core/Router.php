<?php

namespace Core;

final class Router
{
  private array $routes = [];

  public function get(string $path, array $action, array $middleware = []): void
  {
    $this->add('GET', $path, $action, $middleware);
  }
  public function post(string $path, array $action, array $middleware = []): void
  {
    $this->add('POST', $path, $action, $middleware);
  }

  private function add(string $method, string $path, array $action, array $middleware): void
  {
    $this->routes[] = compact('method', 'path', 'action', 'middleware');
  }

  public function dispatch(string $method, string $uri): void
  {
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $baseUrl = defined('BASE_URL') ? BASE_URL : '';
    if ($baseUrl !== '' && ($path === $baseUrl || strpos($path, $baseUrl . '/') === 0)) {
      $path = substr($path, strlen($baseUrl)) ?: '/';
    }
    if ($path === '/index.php') $path = '/';
    $path = rtrim($path, '/') ?: '/';

    foreach ($this->routes as $route) {
      if ($route['method'] !== strtoupper($method)) continue;

      $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route['path']);
      if (preg_match('#^' . rtrim($pattern, '/') . '/?$#', $path, $matches)) {
        foreach ($route['middleware'] as $middlewareClass) {
          $middleware = new $middlewareClass();
          $middleware->handle();
        }

        $params = array_filter(
          $matches,
          'is_string',
          ARRAY_FILTER_USE_KEY
        );

        [$controllerClass, $methodName] = $route['action'];
        $controller = new $controllerClass();
        $controller->$methodName(...array_values($params));

        return;
      }
    }

    http_response_code(404);
    echo '404 - Página não encontrada';
  }
}

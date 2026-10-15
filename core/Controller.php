<?php

namespace Core;

abstract class Controller
{
  protected function view(string $view, array $data = [], string $layout = 'app'): void
  {
    View::render($view, $data, $layout);
  }

  protected function notFound(): void
  {
    http_response_code(404);
    echo '404 - Página não encontrada';
  }
}

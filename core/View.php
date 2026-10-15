<?php

namespace Core;

final class View
{
  // view: page to be rendered
  // data: array of data to be passed to the view
  // layout: layout to be used for show errors
  public static function render(string $view, array $data = [], string $layout = 'app'): void
  {
    extract($data, EXTR_SKIP);
    $viewFile = __DIR__ . '/../app/Front/Pages/' . $view . '.php';
    $layoutFile = __DIR__ . '/../app/Front/Layouts/' . $layout . '.php';

    ob_start();
    require $viewFile;
    $content = ob_get_clean();
    require $layoutFile;
  }
}

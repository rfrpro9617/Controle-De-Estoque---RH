<?php

namespace App\Back\Utils;

final class Navigation
{
  private const ITEMS = [
    'Operação' => [
      'dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard'],
      'estoque' => ['label' => 'Estoque', 'icon' => 'boxes'],
      'distribuido-aos-setores' => ['label' => 'Distribuído aos Setores', 'icon' => 'landmark'],
      'movimentacoes' => ['label' => 'Movimentações', 'icon' => 'arrow-left-right'],
    ],
    'Compras' => [
      'pedidos' => ['label' => 'Pedidos', 'icon' => 'shopping-cart'],
      'gastos' => ['label' => 'Gastos', 'icon' => 'badge-dollar-sign'],
    ],
    'Cadastros' => [
      'materiais' => ['label' => 'Materiais', 'icon' => 'package'],
      'categorias' => ['label' => 'Categorias', 'icon' => 'tags'],
      'setores' => ['label' => 'Setores', 'icon' => 'building-2'],
      'fornecedores' => ['label' => 'Fornecedores', 'icon' => 'truck'],
      'importar-pdf' => ['label' => 'Importar PDF', 'icon' => 'file-up'],
    ],
  ];

  public static function groups(string $basePath): array
  {
    $basePath = rtrim($basePath, '/');
    $groups = [];

    foreach (self::ITEMS as $group => $items) {
      foreach ($items as $slug => $item) {
        $groups[$group][] = [
          ...$item,
          'slug' => $slug,
          'href' => $slug === 'dashboard' ? $basePath : $basePath . '/' . $slug,
        ];
      }
    }

    return $groups;
  }
}

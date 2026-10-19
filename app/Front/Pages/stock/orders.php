<?php

$statusClasses = [
  'em_aprovacao' => 'border-[#f2d5a8] bg-[#fff5e8] text-[#a65b08]',
  'aprovado' => 'border-[#c5d8e8] bg-[#edf4fa] text-[#35658b]',
  'recebido' => 'border-[#b7d8c1] bg-[#edf7ef] text-[#26734a]',
  'parcialmente_recebido' => 'border-[#d6c8eb] bg-[#f4effa] text-[#70519a]',
  'cancelado' => 'border-[#dce3de] bg-[#f3f5f3] text-[#66736b]',
];
?>
<section aria-labelledby="page-title">
  <header class="mb-4 flex flex-wrap items-start justify-between gap-4">
    <div>
      <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Compras</p>
      <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Pedidos</h1>
      <p class="mt-[3px] text-xs text-[#66736b]">Pedidos de compra e seus recebimentos.</p>
    </div>
    <a class="inline-flex min-h-9 items-center rounded-md bg-[#26734a] px-3 text-xs font-semibold text-white no-underline hover:bg-[#1e603d]" href="<?= htmlspecialchars(BASE_URL . '/estoque/importar-pdf', ENT_QUOTES, 'UTF-8') ?>">+ Novo Pedido</a>
  </header>

  <?php if ($invalidSituation): ?>
    <p class="mb-3 rounded-md border border-[#f2d5a8] bg-[#fff5e8] px-3 py-2 text-xs text-[#80500f]" role="status">A situação informada é inválida. Exibindo todos os pedidos.</p>
  <?php endif; ?>

  <p class="mb-3 rounded-md border border-[#c9dbe2] bg-[#eaf1f3] px-3 py-[9px] text-[11px] text-[#334c56]">
    Um pedido de compra não altera o estoque. O estoque central só muda quando uma entrada é efetivamente registrada, inclusive em recebimentos parciais.
  </p>

  <form action="<?= htmlspecialchars(BASE_URL . '/estoque/pedidos', ENT_QUOTES, 'UTF-8') ?>" method="get" class="mb-3 flex flex-wrap gap-2 rounded-lg border border-[#dce3de] bg-white p-2 shadow-sm">
    <label class="relative min-w-[220px] flex-1">
      <span class="sr-only">Pesquisar pedido ou fornecedor</span>
      <i class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#829087]" data-lucide="search" aria-hidden="true"></i>
      <input class="h-9 w-full rounded-md border border-[#dce3de] bg-white pl-8 pr-3 text-xs outline-none focus:border-[#26734a]" type="search" name="busca" placeholder="Pesquisar pedido ou fornecedor..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>
      <span class="sr-only">Filtrar por situação</span>
      <select class="h-9 min-w-[180px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="situacao">
        <option value="">Todas as situações</option>
        <?php foreach ($situations as $value => $label): ?>
          <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedSituation === $value ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="h-9 rounded-md border border-[#dce3de] bg-[#f8faf8] px-3 text-xs font-medium text-[#344239] hover:bg-[#f0f4f1]" type="submit">Filtrar</button>
  </form>

  <div class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm">
    <?php if ($orders === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status"><?= $search !== '' || $selectedSituation !== '' ? 'Nenhum pedido corresponde aos filtros.' : 'Nenhum pedido cadastrado.' ?></p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-3 py-[9px]" scope="col">Pedido</th>
              <th class="px-3 py-[9px]" scope="col">Data</th>
              <th class="px-3 py-[9px]" scope="col">Fornecedor</th>
              <th class="px-3 py-[9px]" scope="col">Situação</th>
              <th class="px-3 py-[9px] text-right" scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
              <tr class="border-t border-[#dce3de]">
                <td class="px-3 py-[10px] font-semibold text-[#18221b]">#<?= htmlspecialchars($order->number, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px] text-[#66736b]"><?= htmlspecialchars(date('d/m/Y', strtotime($order->date)), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px] text-[#18221b]"><?= htmlspecialchars($order->supplierName, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px]">
                  <span class="inline-flex rounded-full border px-2 py-[3px] text-[10px] font-medium <?= $statusClasses[$order->situation] ?? 'border-[#dce3de] bg-[#f3f5f3] text-[#66736b]' ?>">
                    <?= htmlspecialchars($order->situationLabel(), ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>
                <td class="px-3 py-[10px] text-right">
                  <a class="font-medium text-[#26734a] no-underline hover:underline" href="<?= htmlspecialchars(BASE_URL . '/estoque/pedidos/' . $order->id, ENT_QUOTES, 'UTF-8') ?>">Ver detalhes</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

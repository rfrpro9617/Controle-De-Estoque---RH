<section aria-labelledby="page-title">
  <header class="mb-5">
    <a class="text-xs font-medium text-[#26734a] no-underline hover:underline" href="<?= htmlspecialchars(BASE_URL . '/estoque/pedidos', ENT_QUOTES, 'UTF-8') ?>">← Voltar para pedidos</a>
    <p class="mb-[3px] mt-4 text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Compras</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Pedido #<?= htmlspecialchars($order->number, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Detalhes do pedido de compra.</p>
  </header>

  <dl class="grid max-w-3xl gap-px overflow-hidden rounded-lg border border-[#dce3de] bg-[#dce3de] sm:grid-cols-2">
    <div class="bg-white p-4">
      <dt class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Número do pedido</dt>
      <dd class="mb-0 mt-1 text-sm font-semibold text-[#18221b]">#<?= htmlspecialchars($order->number, ENT_QUOTES, 'UTF-8') ?></dd>
    </div>
    <div class="bg-white p-4">
      <dt class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Data</dt>
      <dd class="mb-0 mt-1 text-sm text-[#18221b]"><?= htmlspecialchars(date('d/m/Y', strtotime($order->date)), ENT_QUOTES, 'UTF-8') ?></dd>
    </div>
    <div class="bg-white p-4">
      <dt class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Fornecedor</dt>
      <dd class="mb-0 mt-1 text-sm text-[#18221b]"><?= htmlspecialchars($order->supplierName, ENT_QUOTES, 'UTF-8') ?></dd>
    </div>
    <div class="bg-white p-4">
      <dt class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Situação</dt>
      <dd class="mb-0 mt-1 text-sm text-[#18221b]"><?= htmlspecialchars($order->situationLabel(), ENT_QUOTES, 'UTF-8') ?></dd>
    </div>
    <div class="bg-white p-4 sm:col-span-2">
      <dt class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Solicitante</dt>
      <dd class="mb-0 mt-1 text-sm text-[#18221b]"><?= $order->requesterId === null ? 'Não informado' : '#' . $order->requesterId ?></dd>
    </div>
  </dl>
</section>

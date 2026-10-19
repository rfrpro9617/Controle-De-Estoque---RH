<?php
$monthDate = DateTimeImmutable::createFromFormat('!Y-m', $filters->month);
$monthLabel = $monthDate === false
  ? $filters->month
  : ucfirst($monthDate->format('F/Y'));
$monthLabel = str_replace(
  ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
  $monthLabel
);
?>
<section aria-labelledby="page-title">
  <header class="mb-4">
    <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Compras</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Gastos</h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Acompanhamento dos gastos com materiais.</p>
  </header>

  <?php if ($filters->hasInvalidInput): ?>
    <p class="mb-3 rounded-md border border-[#f2d5a8] bg-[#fff5e8] px-3 py-2 text-xs text-[#80500f]" role="status">Um ou mais filtros eram inválidos e foram ignorados.</p>
  <?php endif; ?>

  <form action="<?= htmlspecialchars(BASE_URL . '/estoque/gastos', ENT_QUOTES, 'UTF-8') ?>" method="get" class="mb-3 flex flex-wrap gap-2 rounded-lg border border-[#dce3de] bg-white p-2 shadow-sm">
    <label>
      <span class="sr-only">Filtrar por mês</span>
      <input class="h-9 rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" type="month" name="mes" value="<?= htmlspecialchars($filters->month, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>
      <span class="sr-only">Filtrar por categoria</span>
      <select class="h-9 min-w-[180px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category->id ?>" <?= $filters->categoryId === $category->id ? 'selected' : '' ?>><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="sr-only">Filtrar por material</span>
      <select class="h-9 min-w-[200px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="material">
        <option value="">Todos os materiais</option>
        <?php foreach ($materials as $material): ?>
          <option value="<?= $material->id ?>" <?= $filters->materialId === $material->id ? 'selected' : '' ?>>
            <?= htmlspecialchars($material->name . ' (' . $material->code . ')', ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="h-9 rounded-md border border-[#dce3de] bg-[#f8faf8] px-3 text-xs font-medium text-[#344239] hover:bg-[#f0f4f1]" type="submit">Filtrar</button>
  </form>

  <section class="mb-4 flex max-w-3xl items-center gap-3 rounded-lg border border-[#dce3de] bg-white p-4 shadow-sm" aria-label="Total de gastos no período">
    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-md bg-[#e7f3eb] text-[#26734a]">
      <i class="h-5 w-5" data-lucide="wallet" aria-hidden="true"></i>
    </span>
    <div>
      <p class="m-0 text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Total gasto · <?= htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8') ?></p>
      <p class="m-0 font-['Manrope'] text-2xl font-extrabold text-[#18221b]">R$ <?= number_format($total, 2, ',', '.') ?></p>
      <p class="m-0 text-[11px] text-[#66736b]">Compras registradas no período selecionado.</p>
    </div>
  </section>

  <section class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm" aria-labelledby="expense-items-title">
    <h2 class="m-0 border-b border-[#dce3de] px-4 py-3 text-xs font-semibold text-[#26352c]" id="expense-items-title">Gastos por item</h2>
    <?php if ($items === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status">Nenhum gasto encontrado para os filtros selecionados.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-3 py-[9px]" scope="col">Item</th>
              <th class="px-3 py-[9px] text-right" scope="col">Quantidade recebida</th>
              <th class="px-3 py-[9px] text-right" scope="col">Valor gasto</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr class="border-t border-[#dce3de]">
                <td class="px-3 py-[10px] font-medium text-[#18221b]">
                  <?= htmlspecialchars($item->materialName, ENT_QUOTES, 'UTF-8') ?>
                  <span class="ml-1 text-[#66736b]"><?= htmlspecialchars($item->materialCode, ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td class="px-3 py-[10px] text-right text-[#344239]"><?= number_format($item->quantity, 0, ',', '.') ?></td>
                <td class="px-3 py-[10px] text-right font-semibold text-[#18221b]">R$ <?= number_format($item->total, 2, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</section>

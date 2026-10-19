<section aria-labelledby="page-title">
  <header class="mb-5">
    <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Operação</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Movimentações</h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Histórico de movimentações de materiais.</p>
  </header>

  <?php if ($filters->hasInvalidInput): ?>
    <p class="mb-3 rounded-md border border-[#f2d5a8] bg-[#fff5e8] px-3 py-2 text-xs text-[#80500f]" role="status">Um ou mais filtros eram inválidos e foram ignorados.</p>
  <?php endif; ?>

  <form action="<?= htmlspecialchars(BASE_URL . '/estoque/movimentacoes', ENT_QUOTES, 'UTF-8') ?>" method="get" class="mb-3 flex flex-wrap gap-2 rounded-lg border border-[#dce3de] bg-white p-2 shadow-sm">
    <label class="relative min-w-[200px] flex-1">
      <span class="sr-only">Pesquisar material, código, observação ou pedido</span>
      <i class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#829087]" data-lucide="search" aria-hidden="true"></i>
      <input class="h-9 w-full rounded-md border border-[#dce3de] bg-white pl-8 pr-3 text-xs outline-none focus:border-[#26734a]" type="search" name="busca" placeholder="Pesquisar material..." value="<?= htmlspecialchars($filters->search, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>
      <span class="sr-only">Filtrar por tipo</span>
      <select class="h-9 min-w-[135px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="tipo">
        <option value="">Todos os tipos</option>
        <?php foreach ($types as $value => $label): ?>
          <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $filters->type === $value ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="sr-only">Filtrar por setor</span>
      <select class="h-9 min-w-[155px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="setor">
        <option value="">Todos os setores</option>
        <?php foreach ($sectors as $sector): ?>
          <option value="<?= $sector->id ?>" <?= $filters->sectorId === $sector->id ? 'selected' : '' ?>>
            <?= htmlspecialchars($sector->name . (!$sector->active ? ' (Inativo)' : ''), ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="sr-only">Filtrar por categoria</span>
      <select class="h-9 min-w-[165px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category->id ?>" <?= $filters->categoryId === $category->id ? 'selected' : '' ?>><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      <span class="sr-only">Data inicial</span>
      <input class="h-9 rounded-md border border-[#dce3de] bg-white px-2 text-xs text-[#344239] outline-none focus:border-[#26734a]" type="date" name="de" aria-label="Data inicial" value="<?= htmlspecialchars($filters->dateFrom, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <span class="self-center text-xs text-[#66736b]">até</span>
    <label>
      <span class="sr-only">Data final</span>
      <input class="h-9 rounded-md border border-[#dce3de] bg-white px-2 text-xs text-[#344239] outline-none focus:border-[#26734a]" type="date" name="ate" aria-label="Data final" value="<?= htmlspecialchars($filters->dateTo, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <button class="h-9 rounded-md border border-[#dce3de] bg-[#f8faf8] px-3 text-xs font-medium text-[#344239] hover:bg-[#f0f4f1]" type="submit">Filtrar</button>
  </form>

  <div class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm">
    <?php if ($movements === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status">
        <?= $filters->search !== '' || $filters->type !== '' || $filters->sectorId !== null || $filters->categoryId !== null || $filters->dateFrom !== '' || $filters->dateTo !== '' ? 'Nenhuma movimentação corresponde aos filtros.' : 'Nenhuma movimentação cadastrada.' ?>
      </p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-3 py-[9px]" scope="col">Data</th>
              <th class="px-3 py-[9px]" scope="col">Material</th>
              <th class="px-3 py-[9px]" scope="col">Categoria</th>
              <th class="px-3 py-[9px]" scope="col">Tipo</th>
              <th class="px-3 py-[9px] text-right" scope="col">Quantidade</th>
              <th class="px-3 py-[9px]" scope="col">Setor</th>
              <th class="px-3 py-[9px]" scope="col">Observação</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($movements as $movement): ?>
              <tr class="border-t border-[#dce3de]">
                <td class="whitespace-nowrap px-3 py-[10px] text-[#66736b]"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($movement->date)), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px]">
                  <span class="font-medium text-[#18221b]"><?= htmlspecialchars($movement->materialName, ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="ml-1 text-[#66736b]"><?= htmlspecialchars($movement->materialCode, ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td class="px-3 py-[10px] text-[#66736b]"><?= htmlspecialchars($movement->categoryName, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px]">
                  <span class="inline-flex rounded-full border px-2 py-[3px] text-[10px] font-medium <?= $movement->type === 'entrada' ? 'border-[#b7d8c1] bg-[#edf7ef] text-[#26734a]' : 'border-[#c5d8e8] bg-[#edf4fa] text-[#35658b]' ?>">
                    <?= htmlspecialchars($movement->typeLabel(), ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>
                <td class="px-3 py-[10px] text-right font-semibold text-[#18221b]"><?= number_format($movement->quantity, 0, ',', '.') ?></td>
                <td class="px-3 py-[10px] text-[#66736b]"><?= $movement->sectorName !== null ? htmlspecialchars($movement->sectorName, ENT_QUOTES, 'UTF-8') : '—' ?></td>
                <td class="max-w-[240px] px-3 py-[10px] text-[#66736b]">
                  <?php if ($movement->observation !== null && $movement->observation !== ''): ?>
                    <span title="<?= htmlspecialchars($movement->observation, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($movement->observation, ENT_QUOTES, 'UTF-8') ?></span>
                  <?php elseif ($movement->orderNumber !== null): ?>
                    Pedido #<?= htmlspecialchars($movement->orderNumber, ENT_QUOTES, 'UTF-8') ?>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

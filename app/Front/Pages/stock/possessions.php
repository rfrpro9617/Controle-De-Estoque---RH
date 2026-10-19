<section aria-labelledby="page-title">
  <header class="mb-4">
    <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Operação</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Distribuído aos Setores</h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Materiais atualmente distribuídos aos setores.</p>
  </header>

  <p class="mb-3 rounded-md border border-[#c9dbe2] bg-[#eaf1f3] px-3 py-[9px] text-[11px] text-[#334c56]">
    Materiais em posse dos setores estão fora do estoque central e sob responsabilidade do setor. Eles voltam ao estoque apenas por meio de uma devolução.
  </p>

  <?php if ($filters->hasInvalidInput): ?>
    <p class="mb-3 rounded-md border border-[#f2d5a8] bg-[#fff5e8] px-3 py-2 text-xs text-[#80500f]" role="status">Um ou mais filtros eram inválidos e foram ignorados.</p>
  <?php endif; ?>

  <form action="<?= htmlspecialchars(BASE_URL . '/estoque/distribuido-aos-setores', ENT_QUOTES, 'UTF-8') ?>" method="get" class="mb-3 flex flex-wrap gap-2 rounded-lg border border-[#dce3de] bg-white p-2 shadow-sm">
    <label class="relative min-w-[220px] flex-1">
      <span class="sr-only">Pesquisar material ou setor</span>
      <i class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#829087]" data-lucide="search" aria-hidden="true"></i>
      <input class="h-9 w-full rounded-md border border-[#dce3de] bg-white pl-8 pr-3 text-xs outline-none focus:border-[#26734a]" type="search" name="busca" placeholder="Pesquisar material..." value="<?= htmlspecialchars($filters->search, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>
      <span class="sr-only">Filtrar por setor</span>
      <select class="h-9 min-w-[170px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="setor">
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
      <select class="h-9 min-w-[180px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category->id ?>" <?= $filters->categoryId === $category->id ? 'selected' : '' ?>>
            <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="h-9 rounded-md border border-[#dce3de] bg-[#f8faf8] px-3 text-xs font-medium text-[#344239] hover:bg-[#f0f4f1]" type="submit">Filtrar</button>
  </form>

  <div class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm">
    <?php if ($possessions === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status">
        <?= $filters->search !== '' || $filters->sectorId !== null || $filters->categoryId !== null ? 'Nenhum material corresponde aos filtros.' : 'Nenhum material distribuído aos setores.' ?>
      </p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-3 py-[9px]" scope="col">Setor</th>
              <th class="px-3 py-[9px]" scope="col">Material</th>
              <th class="px-3 py-[9px]" scope="col">Categoria</th>
              <th class="px-3 py-[9px] text-right" scope="col">Quantidade em posse</th>
              <th class="px-3 py-[9px]" scope="col">Última saída</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($possessions as $possession): ?>
              <tr class="border-t border-[#dce3de]">
                <td class="px-3 py-[10px] font-medium text-[#18221b]"><?= htmlspecialchars($possession->sectorName, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px] text-[#18221b]">
                  <span class="font-medium"><?= htmlspecialchars($possession->materialName, ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="ml-1 text-[#66736b]"><?= htmlspecialchars($possession->materialCode, ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td class="px-3 py-[10px] text-[#66736b]"><?= htmlspecialchars($possession->categoryName, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-3 py-[10px] text-right font-semibold text-[#18221b]"><?= number_format($possession->quantity, 0, ',', '.') ?></td>
                <td class="px-3 py-[10px] text-[#66736b]"><?= $possession->lastExit !== null ? htmlspecialchars(date('d/m/Y', strtotime($possession->lastExit)), ENT_QUOTES, 'UTF-8') : '—' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

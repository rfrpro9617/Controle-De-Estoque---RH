<?php
$exitMaterialId = is_string($exitForm['material_id'] ?? null) ? $exitForm['material_id'] : '';
$exitSectorId = is_string($exitForm['setor_id'] ?? null) ? $exitForm['setor_id'] : '';
$exitQuantity = is_string($exitForm['quantidade'] ?? null) ? $exitForm['quantidade'] : '';
$exitDate = is_string($exitForm['data'] ?? null) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $exitForm['data'])
  ? $exitForm['data']
  : date('Y-m-d');
$exitObservation = is_string($exitForm['observacao'] ?? null) ? $exitForm['observacao'] : '';
?>
<section aria-labelledby="page-title">
  <header class="mb-4 flex flex-wrap items-start justify-between gap-4">
    <div>
      <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Operação</p>
      <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Estoque</h1>
      <p class="mt-[3px] text-xs text-[#66736b]">Materiais disponíveis no estoque central.</p>
    </div>
    <button class="inline-flex min-h-9 items-center rounded-md border border-[#dce3de] bg-white px-3 text-xs font-semibold text-[#26352c] shadow-sm hover:bg-[#f8faf8]" type="button" data-open-exit-dialog>Nova saída</button>
  </header>

  <?php if ($feedback !== null): ?>
    <p class="mb-3 rounded-md border px-3 py-2 text-xs <?= str_starts_with($feedback, 'Saída registrada') ? 'border-[#b7d8c1] bg-[#edf7ef] text-[#174d31]' : 'border-[#efc1bb] bg-[#fff1ef] text-[#8a2e22]' ?>" role="<?= str_starts_with($feedback, 'Saída registrada') ? 'status' : 'alert' ?>">
      <?= htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8') ?>
    </p>
  <?php endif; ?>

  <?php if ($filters->hasInvalidInput): ?>
    <p class="mb-3 rounded-md border border-[#f2d5a8] bg-[#fff5e8] px-3 py-2 text-xs text-[#80500f]" role="status">Um ou mais filtros eram inválidos e foram ignorados.</p>
  <?php endif; ?>

  <dialog class="w-[calc(100%-2rem)] max-w-lg rounded-lg border border-[#dce3de] bg-white p-0 text-[#18221b] shadow-xl backdrop:bg-slate-900/70" aria-labelledby="exit-dialog-title" data-exit-dialog>
    <form action="<?= htmlspecialchars(BASE_URL . '/estoque/estoque/saida', ENT_QUOTES, 'UTF-8') ?>" method="post" class="grid gap-4 p-5">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
      <header class="flex items-start justify-between border-b border-[#e5eae6] pb-3">
        <div>
          <h2 class="m-0 font-['Manrope'] text-base font-bold" id="exit-dialog-title">Nova saída para setor</h2>
          <p class="mt-1 text-xs text-[#66736b]">A saída reduz o estoque central e transfere a responsabilidade do material para o setor.</p>
        </div>
        <button class="rounded p-1 text-lg leading-none text-[#66736b] hover:bg-[#f2f6f3]" type="button" aria-label="Fechar" data-close-exit-dialog>&times;</button>
      </header>

      <div>
        <label class="mb-1 block text-xs font-semibold text-[#344239]" for="exit-material">Material</label>
        <select class="h-10 w-full rounded-md border border-[#cbd5ce] bg-white px-3 text-sm outline-none focus:border-[#26734a]" id="exit-material" name="material_id" required>
          <option value="">Selecione o material</option>
          <?php foreach ($exitMaterials as $material): ?>
            <option
              value="<?= $material->id ?>"
              data-stock="<?= $material->currentStock ?>"
              <?= $exitMaterialId === (string) $material->id ? 'selected' : '' ?>>
              <?= htmlspecialchars($material->code . ' - ' . $material->name . ' (' . number_format($material->currentStock, 0, ',', '.') . ' disponíveis)', ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if ($exitMaterials === []): ?>
          <p class="mt-1 text-[11px] text-[#a65b08]">Não há materiais ativos cadastrados para saída.</p>
        <?php endif; ?>
      </div>

      <div class="grid gap-3 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-xs font-semibold text-[#344239]" for="exit-quantity">Quantidade</label>
          <input class="h-10 w-full rounded-md border border-[#cbd5ce] bg-white px-3 text-sm outline-none focus:border-[#26734a]" id="exit-quantity" name="quantidade" type="number" min="1" step="1" required value="<?= htmlspecialchars($exitQuantity, ENT_QUOTES, 'UTF-8') ?>">
          <p class="mt-1 text-[11px] text-[#66736b]" data-exit-stock-message>Selecione um material para consultar o saldo disponível.</p>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-[#344239]" for="exit-date">Data</label>
          <input class="h-10 w-full rounded-md border border-[#cbd5ce] bg-white px-3 text-sm outline-none focus:border-[#26734a]" id="exit-date" name="data" type="date" required value="<?= htmlspecialchars($exitDate, ENT_QUOTES, 'UTF-8') ?>">
        </div>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-[#344239]" for="exit-sector">Setor</label>
        <select class="h-10 w-full rounded-md border border-[#cbd5ce] bg-white px-3 text-sm outline-none focus:border-[#26734a]" id="exit-sector" name="setor_id" required>
          <option value="">Selecione o setor</option>
          <?php foreach ($sectors as $sector): ?>
            <option value="<?= $sector->id ?>" <?= $exitSectorId === (string) $sector->id ? 'selected' : '' ?>>
              <?= htmlspecialchars($sector->name . ($sector->acronym !== '' ? ' (' . $sector->acronym . ')' : ''), ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if ($sectors === []): ?>
          <p class="mt-1 text-[11px] text-[#a65b08]">Não há setores ativos cadastrados.</p>
        <?php endif; ?>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-[#344239]" for="exit-observation">Observação</label>
        <textarea class="min-h-20 w-full rounded-md border border-[#cbd5ce] bg-white px-3 py-2 text-sm outline-none focus:border-[#26734a]" id="exit-observation" name="observacao" maxlength="65535" placeholder="Informações adicionais sobre a entrega"><?= htmlspecialchars($exitObservation, ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <p class="m-0 rounded-md border border-[#e1e8e3] bg-[#f4f7f4] px-3 py-2 text-xs text-[#66736b]">A quantidade registrada será abatida do estoque central e vinculada ao setor selecionado.</p>
      <footer class="flex justify-end gap-2 border-t border-[#e5eae6] pt-3">
        <button class="h-9 rounded-md border border-[#dce3de] bg-white px-3 text-xs font-medium text-[#344239] hover:bg-[#f8faf8]" type="button" data-close-exit-dialog>Cancelar</button>
        <button class="h-9 rounded-md bg-[#26734a] px-3 text-xs font-semibold text-white hover:bg-[#1e603d] disabled:cursor-not-allowed disabled:opacity-50" type="submit" <?= $exitMaterials === [] || $sectors === [] ? 'disabled' : '' ?>>Registrar saída</button>
      </footer>
    </form>
  </dialog>

  <form action="<?= htmlspecialchars(BASE_URL . '/estoque/estoque', ENT_QUOTES, 'UTF-8') ?>" method="get" class="mb-3 flex flex-wrap gap-2 rounded-lg border border-[#dce3de] bg-white p-2 shadow-sm">
    <label class="relative min-w-[220px] flex-1">
      <span class="sr-only">Pesquisar material, código ou categoria</span>
      <i class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#829087]" data-lucide="search" aria-hidden="true"></i>
      <input class="h-9 w-full rounded-md border border-[#dce3de] bg-white pl-8 pr-3 text-xs outline-none focus:border-[#26734a]" type="search" name="busca" placeholder="Pesquisar material..." value="<?= htmlspecialchars($filters->search, ENT_QUOTES, 'UTF-8') ?>">
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
      <span class="sr-only">Filtrar por status</span>
      <select class="h-9 min-w-[160px] rounded-md border border-[#dce3de] bg-white px-3 text-xs text-[#344239] outline-none focus:border-[#26734a]" name="status">
        <option value="">Todos os status</option>
        <?php foreach ($statuses as $value => $label): ?>
          <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $filters->status === $value ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="h-9 rounded-md border border-[#dce3de] bg-[#f8faf8] px-3 text-xs font-medium text-[#344239] hover:bg-[#f0f4f1]" type="submit">Filtrar</button>
  </form>

  <div class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm">
    <?php if ($materials === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status"><?= $filters->search !== '' || $filters->categoryId !== null || $filters->status !== '' ? 'Nenhum material corresponde aos filtros.' : 'Nenhum material cadastrado.' ?></p>
    <?php else: ?>
      <?php
      $statusClasses = [
        'disponivel' => 'border-[#b7d8c1] bg-[#edf7ef] text-[#26734a]',
        'estoque_baixo' => 'border-[#f2d5a8] bg-[#fff5e8] text-[#a65b08]',
        'sem_estoque' => 'border-[#f0c4c4] bg-[#fbeeee] text-[#a33a3a]',
        'inativo' => 'border-[#dce3de] bg-[#f3f5f3] text-[#66736b]',
      ];
      ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-4 py-[9px]" scope="col">Código</th>
              <th class="px-4 py-[9px]" scope="col">Material</th>
              <th class="px-4 py-[9px]" scope="col">Categoria</th>
              <th class="px-4 py-[9px] text-right" scope="col">Estoque atual</th>
              <th class="px-4 py-[9px] text-right" scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($materials as $material): ?>
              <?php $status = $material->status(); ?>
              <tr class="border-t border-[#dce3de]">
                <td class="px-4 py-[10px] text-[#66736b]"><?= htmlspecialchars($material->code, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-4 py-[10px] font-medium text-[#18221b]"><?= htmlspecialchars($material->name, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-4 py-[10px] text-[#66736b]"><?= htmlspecialchars($material->categoryName, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-4 py-[10px] text-right font-semibold text-[#18221b]"><?= number_format($material->currentStock, 0, ',', '.') ?></td>
                <td class="px-4 py-[10px] text-right">
                  <span class="inline-flex rounded-full border px-2 py-[3px] text-[10px] font-medium <?= $statusClasses[$status] ?>">
                    <?= htmlspecialchars($statuses[$status], ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
  const exitDialog = document.querySelector('[data-exit-dialog]');
  const openExitDialog = document.querySelector('[data-open-exit-dialog]');
  const exitMaterialSelect = document.getElementById('exit-material');
  const exitStockMessage = document.querySelector('[data-exit-stock-message]');

  const updateExitStockMessage = () => {
    const selectedOption = exitMaterialSelect.selectedOptions[0];
    if (!selectedOption || !selectedOption.value) {
      exitStockMessage.textContent = 'Selecione um material para consultar o saldo disponível.';
      return;
    }

    const currentStock = Number(selectedOption.dataset.stock);
    exitStockMessage.textContent = `Saldo disponível: ${currentStock.toLocaleString('pt-BR')}.`;
  };

  openExitDialog.addEventListener('click', () => exitDialog.showModal());
  document.querySelectorAll('[data-close-exit-dialog]').forEach((button) => {
    button.addEventListener('click', () => exitDialog.close());
  });
  exitMaterialSelect.addEventListener('change', updateExitStockMessage);
  updateExitStockMessage();

  <?php if ($feedback !== null && !str_starts_with($feedback, 'Saída registrada')): ?>
    exitDialog.showModal();
  <?php endif; ?>
</script>

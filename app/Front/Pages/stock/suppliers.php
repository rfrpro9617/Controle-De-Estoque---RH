<section aria-labelledby="page-title">
  <header class="mb-5">
    <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Cadastros</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Fornecedores</h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Empresas fornecedoras dos materiais adquiridos.</p>
  </header>

  <div class="overflow-hidden rounded-lg border border-[#dce3de] bg-white shadow-sm">
    <?php if ($suppliers === []): ?>
      <p class="m-0 px-5 py-8 text-center text-sm text-[#66736b]" role="status">Nenhum fornecedor cadastrado.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[420px] border-collapse text-left text-xs">
          <thead class="bg-[#f8faf8] text-[9px] font-semibold uppercase tracking-[0.08em] text-[#728078]">
            <tr>
              <th class="px-4 py-[9px]" scope="col">Nome</th>
              <th class="px-4 py-[9px] text-right" scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($suppliers as $supplier): ?>
              <tr class="border-t border-[#dce3de]">
                <td class="px-4 py-[10px] font-medium text-[#18221b]"><?= htmlspecialchars($supplier->name, ENT_QUOTES, 'UTF-8') ?></td>
                <td class="px-4 py-[10px] text-right">
                  <span class="inline-flex rounded-full border px-2 py-[3px] text-[10px] font-medium <?= $supplier->active ? 'border-[#b7d8c1] bg-[#edf7ef] text-[#26734a]' : 'border-[#dce3de] bg-[#f3f5f3] text-[#66736b]' ?>">
                    <?= $supplier->active ? 'Ativo' : 'Inativo' ?>
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

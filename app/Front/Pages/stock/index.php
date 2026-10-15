<section aria-labelledby="page-title">
  <header class="mb-5 flex items-start justify-between gap-5">
    <div>
      <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Sistema de Estoque</p>
      <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title"><?= htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="mt-[3px] text-xs text-[#66736b]">
        Visualize e gerencie as informações desta seção.
      </p>
    </div>
  </header>
  <div class="grid min-h-[180px] content-center justify-items-center rounded-lg border border-dashed border-[#d6dfd8] bg-white/55 text-center text-[#718078]" aria-label="Área de conteúdo da seção">
    <i class="mb-[9px] h-[22px] w-[22px] text-[#48805e]" data-lucide="<?= htmlspecialchars($section['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
    <p class="m-0 text-[13px] font-semibold text-[#314139]">Área de conteúdo</p>
    <span class="mt-[3px] text-[11px]">Esta seção está pronta para receber sua tela.</span>
  </div>
</section>
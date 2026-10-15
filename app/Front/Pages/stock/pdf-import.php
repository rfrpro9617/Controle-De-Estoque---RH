<section aria-labelledby="page-title">
  <header class="mb-5">
    <p class="mb-[3px] text-[10px] font-semibold uppercase tracking-[0.08em] text-[#728078]">Sistema de Estoque</p>
    <h1 class="m-0 font-['Manrope'] text-[21px] font-extrabold leading-[1.3] md:text-[23px]" id="page-title">Importar PDF</h1>
    <p class="mt-[3px] text-xs text-[#66736b]">Envie um pedido em PDF para extrair o texto e preparar a importação dos materiais.</p>
  </header>

  <div class="max-w-3xl rounded-lg border border-[#dce3de] bg-white p-5 shadow-sm md:p-7">
    <form action="<?= htmlspecialchars(BASE_URL . '/estoque/importar-pdf', ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" class="grid gap-5">
      <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
      <div>
        <label for="pdf" class="mb-2 block text-sm font-semibold text-[#26352c]">Arquivo PDF</label>
        <input
          class="block w-full cursor-pointer rounded-md border border-[#cbd5ce] bg-[#f8faf8] text-sm text-[#314139] file:mr-4 file:border-0 file:bg-[#e7f3eb] file:px-4 file:py-3 file:font-semibold file:text-[#174d31] hover:file:bg-[#d9ecdf]"
          id="pdf"
          name="pdf"
          type="file"
          accept=".pdf,application/pdf"
          required>
        <p class="mt-2 text-xs text-[#66736b]">Tamanho máximo: 10 MB. O arquivo é processado nesta requisição e não fica armazenado.</p>
      </div>
      <button class="inline-flex min-h-10 w-fit items-center justify-center gap-2 rounded-md border-0 bg-[#26734a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1d5d3b] focus:outline-none focus:ring-2 focus:ring-[#26734a] focus:ring-offset-2" type="submit">
        <i class="h-4 w-4" data-lucide="file-up" aria-hidden="true"></i>
        Ler PDF
      </button>
    </form>
  </div>

  <?php if (isset($feedback)): ?>
    <?php
    $feedbackClasses = match ($feedbackType ?? 'error') {
      'success' => 'border-[#b7d8c1] bg-[#edf7ef] text-[#174d31]',
      'warning' => 'border-[#ead7a5] bg-[#fff8e6] text-[#72540d]',
      default => 'border-[#efc1bb] bg-[#fff1ef] text-[#8a2e22]',
    };
    ?>
    <div class="mt-5 max-w-3xl rounded-md border px-4 py-3 text-sm <?= $feedbackClasses ?>" role="<?= ($feedbackType ?? 'error') === 'error' ? 'alert' : 'status' ?>">
      <?= htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($extractedText)): ?>
    <section class="mt-5 max-w-3xl rounded-lg border border-[#dce3de] bg-white p-5 shadow-sm md:p-7" aria-labelledby="extracted-text-title">
      <header class="mb-3 flex flex-wrap items-baseline justify-between gap-2">
        <h2 class="m-0 font-['Manrope'] text-base font-bold text-[#26352c]" id="extracted-text-title">Texto extraído</h2>
        <span class="text-xs text-[#66736b]"><?= number_format($extractedCharacterCount, 0, ',', '.') ?> caracteres</span>
      </header>
      <pre class="max-h-[480px] overflow-auto whitespace-pre-wrap break-words rounded-md bg-[#f8faf8] p-4 text-xs leading-relaxed text-[#314139]"><?= htmlspecialchars($extractedText, ENT_QUOTES, 'UTF-8') ?></pre>
      <?php if ($textPreviewTruncated): ?>
        <p class="mb-0 mt-3 text-xs text-[#66736b]">A prévia foi limitada aos primeiros 12.000 caracteres. O restante foi extraído, mas ainda não é persistido.</p>
      <?php endif; ?>
    </section>
  <?php endif; ?>
</section>

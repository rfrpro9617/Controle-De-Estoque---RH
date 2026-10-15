<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#f4f7f4">
  <title><?= htmlspecialchars($title ?? 'Sistema de Estoque', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.24/dist/full.min.css" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
</head>

<body class="min-h-screen bg-[#f4f7f4] font-['DM_Sans'] text-slate-900">
  <?php require __DIR__ . '/../Components/navbar.php'; ?>
  <div class="flex min-h-[calc(100vh-5rem)]">
    <button class="fixed inset-x-0 bottom-0 top-20 z-[19] hidden border-0 bg-slate-900/35 md:hidden" type="button" aria-label="Fechar menu" data-sidebar-close></button>
    <aside class="fixed inset-y-0 left-0 top-20 z-20 flex h-[calc(100vh-5rem)] w-[201px] -translate-x-full flex-col overflow-y-auto border-r border-[#dce3de] bg-white px-[10px] pb-[14px] pt-[18px] transition-transform duration-200 motion-reduce:transition-none md:sticky md:top-20 md:translate-x-0" id="inventory-sidebar" aria-label="Menu do sistema de estoque">
      <nav class="flex-1">
        <?php foreach ($navigationGroups as $group => $items): ?>
          <div class="mb-[22px] last:mb-0">
            <p class="mb-[6px] px-[7px] text-[9px] font-semibold uppercase tracking-[0.09em] text-[#77837b]"><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8') ?></p>
            <ul class="m-0 grid list-none gap-0.5 p-0">
              <?php foreach ($items as $item): ?>
                <li>
                  <a
                    class="flex min-h-[34px] items-center gap-[9px] rounded-md border-l-[3px] px-[5px] text-xs no-underline transition-colors hover:bg-[#f2f6f3] <?= $activeSection === $item['slug'] ? 'border-[#26734a] bg-[#e7f3eb] text-[#174d31] font-semibold' : 'border-transparent text-[#18221b]' ?>"
                    href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                    <?= $activeSection === $item['slug'] ? 'aria-current="page"' : '' ?>>
                    <i class="h-[15px] w-[15px] shrink-0" data-lucide="<?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </nav>

    </aside>

    <div class="min-w-0 flex-1">
      <header class="sticky top-0 z-10 flex h-[52px] items-center gap-[11px] border-b border-[#dce3de] bg-white px-4 text-[13px] font-semibold md:hidden">
        <button
          class="grid h-8 w-8 place-items-center rounded-md border-0 bg-transparent text-[#26352c] hover:bg-[#f2f6f3]"
          type="button"
          aria-label="Abrir menu"
          aria-controls="inventory-sidebar"
          aria-expanded="false"
          data-sidebar-toggle>
          <i class="h-[19px] w-[19px]" data-lucide="menu" aria-hidden="true"></i>
        </button>
        <span>Sistema de Estoque</span>
      </header>
      <main class="mx-auto w-full max-w-[1256px] px-[17px] pb-9 pt-[23px] md:px-8 md:pb-12 md:pt-[27px]" id="main-content">
        <?= $content ?>
      </main>
    </div>
  </div>

  <script>
    lucide.createIcons();

    const sidebar = document.getElementById('inventory-sidebar');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const backdrop = document.querySelector('[data-sidebar-close]');

    const setSidebarOpen = (isOpen) => {
      sidebar.classList.toggle('-translate-x-full', !isOpen);
      sidebar.classList.toggle('translate-x-0', isOpen);
      backdrop.classList.toggle('hidden', !isOpen);
      backdrop.classList.toggle('block', isOpen);
      toggle.setAttribute('aria-expanded', String(isOpen));
      toggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
    };

    toggle.addEventListener('click', () => {
      setSidebarOpen(sidebar.classList.contains('-translate-x-full'));
    });
    backdrop.addEventListener('click', () => setSidebarOpen(false));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') setSidebarOpen(false);
    });
  </script>
</body>

</html>
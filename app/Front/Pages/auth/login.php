<main class="grid min-h-screen place-items-center bg-base-200 p-4">
  <section class="card grid w-full max-w-5xl overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-xl lg:grid-cols-[0.92fr_1.08fr]" aria-label="Acesso ao sistema">
    <div class="hidden flex-col justify-between bg-[#234d3c] p-10 text-[#f5f5e9] lg:flex">
      <div class="flex w-full items-center justify-center gap-3">
        <a href="<?= BASE_URL ?>/" aria-label="Página inicial">
          <img class="h-16 w-20 rounded-md object-contain" src="<?= BASE_URL ?>/app/Front/Assets/images/logoninfaabelha.jpg" alt="NINFA">
        </a>
        <a href="https://www.ufrgs.br/fagroz/" aria-label="FAGROZ - UFRGS">
          <img class="h-16 w-28 object-contain lg:brightness-0 lg:invert" src="<?= BASE_URL ?>/app/Front/Assets/images/logo_fagroz.png" alt="FAGROZ">
        </a>
      </div>

      <div>
        <span class="text-xs font-bold tracking-[0.14em] text-[#c5d29e]">GESTÃO DE ESTOQUE DA FAGROZ</span>
        <h1 class="mt-4 font-['Manrope'] text-4xl font-bold leading-tight">Insumos da FAGROZ,<br>sempre sob controle.</h1>
        <p class="mt-4 max-w-xs text-sm leading-7 text-[#d0dbd0]">Acesse sua conta para continuar no sistema.</p>
      </div>

      <div class="flex items-center gap-2 text-xs text-[#d1dbd0]">
        <span class="h-2 w-2 rounded-full bg-[#d8e89c]"></span>
        <span>Portal interno</span>
        <span class="text-[#839b84]">/</span>
        <span><?= date('Y') ?></span>
      </div>
    </div>

    <div class="card-body flex min-h-[560px] items-center justify-center px-6 py-8 sm:px-12 lg:px-16">
      <div class="my-auto w-full max-w-sm">
        <div class="mb-10 flex items-center justify-center gap-3 lg:hidden">
          <a href="<?= BASE_URL ?>/" aria-label="Página inicial">
            <img class="h-16 w-20 rounded-md object-contain" src="<?= BASE_URL ?>/app/Front/Assets/images/logoninfaabelha.jpg" alt="NINFA">
          </a>
          <a href="https://www.ufrgs.br/fagroz/" aria-label="FAGROZ - UFRGS">
            <img class="h-16 w-28 object-contain" src="<?= BASE_URL ?>/app/Front/Assets/images/logo_fagroz.png" alt="FAGROZ">
          </a>
        </div>

        <div class="mb-8">
          <span class="text-xs font-bold tracking-[0.14em] text-success">BEM-VINDO DE VOLTA</span>
          <h2 class="mt-2 font-['Manrope'] text-2xl font-bold text-base-content">Entrar na sua conta</h2>
          <p class="mt-2 text-sm leading-6 text-base-content/60">Informe seus dados de acesso para continuar.</p>
        </div>

        <form action="<?= BASE_URL ?>/login" method="post" class="grid gap-4">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <label class="form-control w-full">
            <span class="label"><span class="label-text font-semibold">E-mail</span></span>
            <input class="input input-bordered w-full rounded-md focus:border-success focus:outline-success" type="email" name="email" placeholder="voce@ufrgs.br" autocomplete="username" required>
          </label>

          <div class="form-control w-full">
            <label class="label" for="password"><span class="label-text font-semibold">Senha</span></label>
            <div class="relative">
              <input class="input input-bordered w-full rounded-md pr-12 focus:border-success focus:outline-success" id="password" type="password" name="password" placeholder="Sua senha" autocomplete="current-password" required>
              <button class="btn btn-ghost btn-sm absolute right-1 top-1/2 -translate-y-1/2" type="button" data-password-toggle="password" aria-controls="password" aria-label="Mostrar senha" aria-pressed="false">
                <i data-lucide="eye" class="h-4 w-4" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <button class="btn mt-3 w-full justify-between rounded-md border-0 bg-[#285c48] text-white hover:bg-[#1f4c3b]" type="submit">
            <span>Entrar</span>
            <i data-lucide="arrow-right" class="h-5 w-5" aria-hidden="true"></i>
          </button>
        </form>
        <p class="mt-6 text-center text-sm text-base-content/70">
          Ainda não tem uma conta?
          <a class="font-semibold text-success hover:underline" href="<?= BASE_URL ?>/cadastro">Cadastre-se</a>
        </p>
      </div>
    </div>
  </section>
</main>
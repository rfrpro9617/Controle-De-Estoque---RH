<main class="grid min-h-screen place-items-center bg-base-200 p-4">
  <section class="card w-full max-w-lg rounded-lg border border-base-300 bg-base-100 shadow-xl" aria-label="Cadastro de usuário">
    <div class="card-body px-6 py-8 sm:px-12">
      <div class="mb-6 text-center">
        <a href="<?= BASE_URL ?>/" aria-label="Voltar ao login">
          <img class="mx-auto h-16 w-20 rounded-md object-contain" src="<?= BASE_URL ?>/app/Front/Assets/images/logoninfaabelha.jpg" alt="NINFA">
        </a>
        <span class="mt-4 block text-xs font-bold tracking-[0.14em] text-success">NOVO USUÁRIO</span>
        <h1 class="mt-2 font-['Manrope'] text-2xl font-bold text-base-content">Criar sua conta</h1>
        <p class="mt-2 text-sm leading-6 text-base-content/60">Preencha os dados para acessar o sistema de estoque.</p>
      </div>

      <form action="<?= BASE_URL ?>/cadastro" method="post" class="grid gap-3">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <label class="form-control w-full">
          <span class="label"><span class="label-text font-semibold">Nome</span></span>
          <input class="input input-bordered w-full rounded-md focus:border-success focus:outline-success" type="text" name="name" value="<?= htmlspecialchars($formName ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="255" autocomplete="name" required>
        </label>

        <label class="form-control w-full">
          <span class="label"><span class="label-text font-semibold">E-mail</span></span>
          <input class="input input-bordered w-full rounded-md focus:border-success focus:outline-success" type="email" name="email" value="<?= htmlspecialchars($formEmail ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="255" placeholder="voce@ufrgs.br" autocomplete="email" required>
        </label>

        <div class="form-control w-full">
          <label class="label" for="password"><span class="label-text font-semibold">Senha</span></label>
          <div class="relative">
            <input class="input input-bordered w-full rounded-md pr-12 focus:border-success focus:outline-success" id="password" type="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" required>
            <button class="btn btn-ghost btn-sm absolute right-1 top-1/2 -translate-y-1/2" type="button" data-password-toggle="password" aria-controls="password" aria-label="Mostrar senha" aria-pressed="false">
              <i data-lucide="eye" class="h-4 w-4" aria-hidden="true"></i>
            </button>
          </div>
          <span class="mt-1 text-xs text-base-content/60">Use ao menos 8 caracteres.</span>
        </div>

        <div class="form-control w-full">
          <label class="label" for="password_confirmation"><span class="label-text font-semibold">Confirme a senha</span></label>
          <div class="relative">
            <input class="input input-bordered w-full rounded-md pr-12 focus:border-success focus:outline-success" id="password_confirmation" type="password" name="password_confirmation" minlength="8" maxlength="72" autocomplete="new-password" required>
            <button class="btn btn-ghost btn-sm absolute right-1 top-1/2 -translate-y-1/2" type="button" data-password-toggle="password_confirmation" aria-controls="password_confirmation" aria-label="Mostrar senha" aria-pressed="false">
              <i data-lucide="eye" class="h-4 w-4" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <button class="btn mt-3 w-full justify-between rounded-md border-0 bg-[#285c48] text-white hover:bg-[#1f4c3b]" type="submit">
          <span>Criar conta</span>
          <i data-lucide="arrow-right" class="h-5 w-5" aria-hidden="true"></i>
        </button>
      </form>

      <p class="mt-5 text-center text-sm text-base-content/70">
        Já tem uma conta?
        <a class="font-semibold text-success hover:underline" href="<?= BASE_URL ?>/">Entrar</a>
      </p>
    </div>
  </section>
</main>

<?php

use Core\Auth;

$userName = trim((string) (Auth::user()?->name ?? ''));
?>
<nav class="navbar border-b border-[#3b6854] bg-[#234d3c] px-4 text-[#f5f5e9] shadow-sm md:px-8" aria-label="Navegação principal">
  <div class="navbar-start">
    <div class="flex items-center gap-2 md:gap-3">
      <a href="<?= BASE_URL ?>/estoque" aria-label="Sistema de Estoque">
        <img class="h-16 w-20 rounded-md object-contain" src="<?= BASE_URL ?>/app/Front/Assets/images/logoninfaabelha.jpg" alt="NINFA">
      </a>
      <a href="https://www.ufrgs.br/fagroz/" aria-label="FAGROZ - UFRGS">
        <img class="h-16 w-28 object-contain brightness-0 invert" src="<?= BASE_URL ?>/app/Front/Assets/images/logo_fagroz.png" alt="FAGROZ">
      </a>
    </div>
  </div>
  <div class="navbar-end gap-3">
    <span class="text-sm">Olá<?= $userName !== '' ? ', ' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') : '' ?></span>
    <form action="<?= BASE_URL ?>/logout" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
      <button class="btn btn-ghost btn-sm text-[#f5f5e9] hover:bg-white/10" type="submit">Sair</button>
    </form>
  </div>
</nav>
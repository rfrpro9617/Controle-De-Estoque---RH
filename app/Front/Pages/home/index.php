<?php
$userName = trim((string) ($userName ?? ''));
?>
<section>
  <!-- TODO: melhorar a mensagem de boas-vindas adicionar saudação com base no horário do dia (bom dia, boa tarde, boa noite) -->
  <h1 class="font-['Manrope'] text-3xl font-bold">Seja bem-vindo<?= $userName !== '' ? ', ' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') : '' ?>!</h1>
</section>
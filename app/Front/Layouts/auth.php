<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#f3f5f0">
  <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.24/dist/full.min.css" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
</head>

<body class="min-h-screen bg-base-200 font-['DM_Sans'] text-base-content" data-theme="light">
  <?= $content ?>
  <?php if (!empty($feedback)): ?>
    <script>
      Swal.fire({
        icon: <?= json_encode($feedbackType ?? 'error') ?>,
        title: <?= json_encode($feedbackTitle ?? 'Falha no login', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        text: <?= json_encode($feedback, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#285c48'
      });
    </script>
  <?php endif; ?>
  <script src="<?= BASE_URL ?>/app/Front/Assets/js/auth.js"></script>
</body>

</html>
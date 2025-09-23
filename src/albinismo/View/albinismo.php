<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../public/css/styles.css">
  <title>Document</title>
</head>
<body>
  <main>

  <div class="orig-card">
    <h2>Simulador de risco de albinismo</h2>
    <p class="subtitle">
      Resultado do cálculo de risco de albinismo para o usuário
      <strong><?= htmlspecialchars($usuarioNome) ?></strong>.
    </p>

    <div style="margin-top:20px;">
      <div class="country-item">
        <div>Pai</div>
        <div><?= isset($riscos['pai']) ? AlbinismoRisco::pct($riscos['pai']) : '—' ?></div>
      </div>
      <div class="country-item">
        <div>Mãe</div>
        <div><?= isset($riscos['mae']) ? AlbinismoRisco::pct($riscos['mae']) : '—' ?></div>
      </div>
      <div class="country-item">
        <div>Risco do filho nascer albino</div>
        <div><?= isset($riscos['filho']) ? AlbinismoRisco::pct($riscos['filho']) : '—' ?></div>
      </div>
    </div>

    <?php if (isset($riscos['filho'])): ?>
      <div role="alert" style="margin-top:12px; color:#ffd1d1;">
        <?= $riscos['filho'] > 0.1
            ? 'Alto risco: filho pode nascer albino'
            : 'Baixo risco ou improvável' ?>
      </div>
    <?php endif; ?>
  </div>
</main>


</body>
</html>
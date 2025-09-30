<?php
session_start();
$chance = $_SESSION['chance_sarda'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Resultado — Sardas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="../../../public/css/styles.css">

  <style>
    /* Centralizar o card */
    .register-screen {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh; /* ocupa a tela toda */
      position: relative;
      z-index: 2; /* garante que fica sobre o vídeo de fundo */
    }
    .register-wrapper {
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>

<body id="page-menu">
  <!-- TOPO -->
  <header class="topbar" id="topbar" role="navigation" aria-label="Navegação principal"> 
    <nav class="nav-inner" id="navInner"> 
      <a href="introducao_genetica_sarda.php" class="nav-link">Introdução</a>
      <a href="listagem_sarda.php" class="nav-link">Lista</a>
      <a href="form_cad_sarda.php" class="nav-link">Novo Cadastro</a> 
    </nav> 
  </header> 

  <!-- VÍDEO DE FUNDO -->
  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline> 
      <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
      Seu navegador não suporta reprodução de vídeo.
    </video>
  </div> 

  <div class="overlay" aria-hidden="true"></div> 

  <!-- RESULTADO -->
  <main class="register-screen" role="main" aria-label="Resultado">
    <!-- ELEMENTO VISUAL -->
    <svg class="bg-decor" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"> 
      <defs> 
        <linearGradient id="g1" x1="0" x2="1" y1="0" y2="1"> 
          <stop offset="0" stop-color="#1CECE7" stop-opacity="0.85"/> 
          <stop offset="1" stop-color="#5DE0FF" stop-opacity="0.6"/> 
        </linearGradient>
        <filter id="f1" x="-20%" y="-20%" width="140%" height="140%"> 
          <feGaussianBlur stdDeviation="30" result="b"/> 
          <feBlend in="SourceGraphic" in2="b"/> 
        </filter>
      </defs> 
      <g filter="url(#f1)">
        <path fill="url(#g1)"
          d="M421.7,345.2Q384,440,290.7,426.3Q197.5,412.6,144,350.5Q90.5,288.4,108.3,198.2Q126.2,108,213.2,86.5Q300.2,65,373.2,114.2Q446.2,163.5,421.7,345.2Z"/>
      </g> 
    </svg> 

    <div class="register-wrapper" role="region" aria-labelledby="resultadoTitle">
      <div class="form-card">
        <div class="form-header">
          <h2 id="resultadoTitle">Resultado</h2>
          <?php if ($chance !== null): ?>
            <p>A chance do filho ter sardas é <strong><?= $chance ?>%</strong></p>
          <?php else: ?>
            <p>Nenhum cálculo foi realizado ainda.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <!-- SCRIPT -->
  <script src="../../../public/script/app.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sardas — Menu</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/styles.css">
</head>
<body id="page-menu">

  <!-- Top navigation bar (será ocultado pelo JS) -->
  <header class="topbar" id="topbar" role="navigation" aria-label="Navegação principal">
    <nav class="nav-inner" id="navInner">
      <a href="listagem_sarda.php" class="nav-link">Lista</a>
      <a href="resultado_sardas.php" class="nav-link">Resultado</a>
    </nav>
  </header>

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
        <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
        Seu navegador não suporta reprodução de vídeo.
    </video>
  </div>

    <div class="overlay" aria-hidden="true"></div>

  <!-- TELA DE CADASTRO -->
  <main class="register-screen" role="main" aria-label="Cadastro">
    <!-- decorative blob SVG (visual only) -->
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
        <path fill="url(#g1)" d="M421.7,345.2Q384,440,290.7,426.3Q197.5,412.6,144,350.5Q90.5,288.4,108.3,198.2Q126.2,108,213.2,86.5Q300.2,65,373.2,114.2Q446.2,163.5,421.7,345.2Z"/>
      </g>
    </svg>
        <center>
    <div class="register-wrapper" role="region" aria-labelledby="formTitle">
      <div class="form-card">
        <div class="form-header">
          <h2 id="formTitle">Descubra a chance do seu filho ter sardas</h2>
          <p>Defina quem da familia tem sardas e descubra quais as chances baseado nos dados informados   </p>
        </div>

        <!-- Inputs apenas visuais (sem submissão real) -->
        

        <div class="row two-cols">
          <div>
            <label>Pai</label>
            <input type="text" name="Pai" placeholder="sim" readonly value="" />
          </div>

          <div>
            <label>Mãe</label>
            <input type="text" name="Mãe" placeholder="não" readonly value="" />
          </div>
        </div>

        <div class="row">
          <label>Calcular a chance do seu filho ter sardas</label>
          <input type="submit" value="calcular"/>
        </div>
      </div>
        
      </div>

      </center>
  </main>

  <script src="../../../public/script/app.js"></script>
</body>
</html>

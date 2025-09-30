<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>BioLineage — Menu</title>

  <!-- Fonte e CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/styles.css">
</head>
<body id="page-menu">

  <?php 
    include __DIR__ . '/navbar.php'; 
  ?>

  <!-- Vídeo de fundo -->
  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
      Seu navegador não suporta reprodução de vídeo.
    </video>
  </div>

  <div class="overlay" aria-hidden="true"></div>

  <!-- MENU -->
  <main class="menu-screen" role="main" aria-label="Menu principal">
    <div class="menu-card" role="region" aria-labelledby="menuTitle">
      <header class="menu-header">
        <h2 id="menuTitle">Bem-vindo(a) ao BioLineage</h2>
        <p>Escolha uma opção para começar</p>
      </header>

      <nav class="menu-grid" aria-label="Opções do menu">
        <a class="module-card" href="../../albinismo/view/albinismo.php" aria-label="Albinismo">
          <div class="mc-title">Albinismo</div>
          <div class="mc-desc">Cálculo para probabilidade de albinismo.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../arvore/View/index.php" aria-label="Projetos">
          <div class="mc-title">Árvore genealógica</div>
          <div class="mc-desc">Cadastre sua árvore genealógica.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../covinhas/View/listagem_covinhas.php" aria-label="Árvore genealógica">
          <div class="mc-title">Covinhas</div>
          <div class="mc-desc">Cálculos de probabilidade de covinhas.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../daltonismo/View/daltonismo.html" aria-label="Simulações">
          <div class="mc-title">Daltonismo</div>
          <div class="mc-desc">Cálculo para probabilidade de daltonismo.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../doencas/View/doenca.html" aria-label="Probabilidade de doenças">
          <div class="mc-title">Doenças genéticas</div>
          <div class="mc-desc">Cálculo para probabilidade de doenças genéticas.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../galeria/View/galeria.php" aria-label="Calculadora de Tipo Sanguíneo">
          <div class="mc-title">Galeria de traços</div>
          <div class="mc-desc">Registro de características hereditárias.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../orelha/index.php" aria-label="Calculadora de Tipo Sanguíneo">
          <div class="mc-title">Orelha</div>
          <div class="mc-desc">Cálculo de probabilidade do lóbulo da orelha ser preso ou solto.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <!-- INACABADO -->
        <a class="module-card" href="../../punnet/View/" aria-label="Árvore genealógica">
          <div class="mc-title">Quadro de Punnett interativo</div>
          <div class="mc-desc"></div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../sardas/View/form_cad_sarda.php" aria-label="Árvore genealógica">
          <div class="mc-title">Sardas</div>
          <div class="mc-desc">Cálculo para probabilidade de sardas.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../origens/View/origens.php" aria-label="Árvore genealógica">
          <div class="mc-title">Simulador de mistura étnica</div>
          <div class="mc-desc">Simular suas origens.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>

        <a class="module-card" href="../../PrevisaoTipoSanguineo/View/tipoSanquineo.html" aria-label="Tipo Sanguíneo">
          <div class="mc-title">Tipo Sanguíneo</div>
          <div class="mc-desc">Calcule o tipo sanguíneo do seu filho.</div>
          <div class="mc-meta"><span class="badge ready">Acessar</span></div>
        </a>
      </nav>
    </div>
  </main>q

  <script src="../../../public/script/app.js"></script>
</body>
</html>

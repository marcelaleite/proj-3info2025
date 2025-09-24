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

  <!-- Navbar (incluída por PHP) -->
  <?php include __DIR__ . '/navbar.php'; ?>

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
        <!-- Dashboard -->
        <a class="module-card" href="dashboard.html" aria-label="Dashboard">
          <div class="mc-title">Dashboard</div>
          <div class="mc-desc">Visão geral do usuário, notificações e atalhos rápidos.</div>
          <div class="mc-meta"><span class="badge ready">Template</span></div>
        </a>

        <!-- Projetos -->
        <a class="module-card" href="projetos.html" aria-label="Projetos">
          <div class="mc-title">Projetos</div>
          <div class="mc-desc">Área para listar e gerenciar projetos e pesquisas.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Simulações -->
        <a class="module-card" href="simulacoes.html" aria-label="Simulações">
          <div class="mc-title">Simulações</div>
          <div class="mc-desc">Módulo para simulação genética (placeholder).</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Árvore genealógica -->
        <a class="module-card" href="../../arvore/View/arvore.html" aria-label="Árvore genealógica">
          <div class="mc-title">Árvore genealógica</div>
          <div class="mc-desc">Template para integrar a visualização e edição de árvores.</div>
          <div class="mc-meta"><span class="badge ready">Template</span></div>
        </a>

        <!-- Cálculo cor dos olhos -->
        <a class="module-card" href="calc-olhos.html" aria-label="Cálculo cor dos olhos">
          <div class="mc-title">Cálculo cor dos olhos</div>
          <div class="mc-desc">Espaço para implementar o cálculo probabilístico dos fenótipos.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Doenças -->
        <a class="module-card" href="../../doencas/View/doenca.html" aria-label="Probabilidade de doenças">
          <div class="mc-title">Cálculo para probabilidade de doenças</div>
          <div class="mc-desc">Espaço para implementar o cálculo probabilístico das doenças hereditárias.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Calculadora de Tipo Sanguíneo (controle em PHP) -->
        <a class="module-card" href="../../app/Control/controle_TipoSanguineo.php" aria-label="Calculadora de Tipo Sanguíneo">
          <div class="mc-title">Calculadora de Tipo Sanguíneo</div>
          <div class="mc-desc">Calcule o tipo sanguíneo do seu filho.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Calculadora de Tipo Sanguíneo (versão HTML) -->
        <a class="module-card" href="../../app/View/tipoSanquineo.html" aria-label="Calculadora de Tipo Sanguíneo">
          <div class="mc-title">Calculadora de Tipo Sanguíneo</div>
          <div class="mc-desc">Calcule o tipo sanguíneo do seu filho.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Albinismo -->
        <a class="module-card" href="../../app/View/albinismo.html" aria-label="Cálculo para probabilidade de albinismo">
          <div class="mc-title">Cálculo para probabilidade de albinismo</div>
          <div class="mc-desc">Espaço para implementar o cálculo probabilístico dos fenótipos.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
        </a>

        <!-- Origem -->
        <a class="module-card" href="../../origens/View/origens.php" aria-label="Origem">
          <div class="mc-title">Origem</div>
          <div class="mc-desc">O Simulador de Origem calcula a mistura de nacionalidades ao longo de suas gerações.</div>
          <div class="mc-meta"><span class="badge ready">Template</span></div>
        </a>

<<<<<<< HEAD:src/cadastro_menu_login/View/menu.html

       
        <a class="module-card" href="../../galeria/View/galeria.php" aria-label="Galeria">
          <div class="mc-title">Galeria</div>
          <div class="mc-desc">Registro de características hereditarias.</div>
          <div class="mc-meta">
            <span class="badge ready">Template</span> 
          </div>
=======
        <!-- Galeria -->
        <a class="module-card module-create" href="novo-modulo.html" aria-label="Criar novo módulo">
          <div style="font-size:22px;">Galeria</div>
          <div>Registro de características hereditárias.</div>
          <div class="mc-meta"><span class="badge dev">Em dev</span></div>
>>>>>>> 470f30a664e9c0cb9b65eec70a1ba39a9e8a5ed0:src/cadastro_menu_login/View/menu.php
        </a>
      </nav>
    </div>
  </main>

  <script src="../../../public/script/app.js"></script>
</body>
</html>

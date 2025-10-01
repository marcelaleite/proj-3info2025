<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Teste Daltonismo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/daltonismo.css">
  <style>
    body {
      color: #fff;
      font-family: 'Poppins', Arial, sans-serif;
      background: #181818;
      margin: 0;
      min-height: 100vh;
    }
    .container {
      max-width: 1100px;
      margin: 80px auto;
      background: rgba(10,10,10,0.65);
      padding: 32px;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }
    h2 {
      font-weight: 700;
      font-size: 2.2rem;
      margin-bottom: 12px;
      letter-spacing: 1px;
    }
    p {
      font-size: 1.1rem;
      margin-bottom: 32px;
    }
    .test-item {
      margin-bottom: 32px;
      text-align: center;
    }
    .test-item img {
      padding: 24px;
      border-radius: 12px;
      background: #222;
      box-shadow: 0 2px 12px rgba(0,0,0,0.2);
      max-width: 100%;
      height: 220px;
      object-fit: cover;
    }
    .test-item input[type="text"] {
      margin-top: 12px;
      padding: 8px 16px;
      border-radius: 8px;
      border: none;
      font-size: 1rem;
      background: #333;
      color: #fff;
      width: 120px;
      text-align: center;
      outline: none;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    .btn-submit {
      justify-self: center;
      grid-column: 1 / -1;
      display: block;
      margin: 32px auto 0 auto;
      padding: 12px 32px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 8px;
      border: none;
      background: #3a86ff;
      color: #fff;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-submit:hover {
      background: #265bb5;
    }
    header.topbar {
      background: #222;
      padding: 16px 0;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .nav-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      
      align-items: center;
    }
    .nav-link {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      padding: 8px 16px;
      border-radius: 6px;
      transition: background 0.2s;
    }
    .nav-link:hover {
      background: #3a86ff;
    }
    .background-video {
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      z-index: -2;
      overflow: hidden;
    }
    .background-video video {
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      opacity: 0.25;
    }
    .overlay {
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: linear-gradient(120deg, #3a86ff 0%, #181818 100%);
      opacity: 0.5;
      z-index: -1;
    }
    @media (max-width: 700px) {
      .container { padding: 12px; }
      h2 { font-size: 1.3rem; }
      .test-item img { height: 120px; }
    }
    .test-item {
      border: 15px solid transparent;
    }
    form {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 15px;                    
}
  </style>
</head>
<body id="page-module-template">
  <header class="topbar" role="navigation" aria-label="Navegação principal">
    <nav class="nav-inner">
      <a href="daltonismo.html" class="nav-link">Voltar</a>
    </nav>
  </header>

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="218955.mp4" type="video/mp4" />
    </video>
  </div>
  <div class="overlay" aria-hidden="true"></div>

  <main>
    <div class="container">
      <h2>Teste de Daltonismo</h2>
      <p>Selecione o número que você consegue enxergar em cada imagem abaixo e clique em "Confirmar".</p>

      <div class="content">
        <form action="../Model/resultado.php" method="post">
        <div class="test-item">
          <img src="../img/teste1.jpg" alt="Teste 1">
          <br>
          <input type="text" name="respostas[]" id="teste1" placeholder=" Número">
        </div>
        <div class="test-item">
          <img src="../img/teste2.jpg" alt="Teste 2">
          <br>
          <input type="text" name="respostas[]" id="teste2" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste3.jpg" alt="Teste 3">
          <br>
          <input type="text" name="respostas[]" id="teste3" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste4.jpg" alt="Teste 4">
          <br>
          <input type="text" name="respostas[]" id="teste4" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste5.jpg" alt="Teste 5">
          <br>
          <input type="text" name="respostas[]" id="teste5" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste6.jpg" alt="Teste 6">
          <br>
          <input type="text" name="respostas[]" id="teste6" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste7.jpg" alt="Teste 7">
          <br>
          <input type="text" name="respostas[]" id="teste7" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste8.jpg" alt="Teste 8">
          <br>
          <input type="text" name="respostas[]" id="teste8" placeholder="Número">
        </div>
        <div class="test-item">
          <img src="../img/teste9.jpg" alt="Teste 9">
          <br>
          <input type="text" name="respostas[]" id="teste9" placeholder="Número">
        </div>
        <button type="submit" class="btn-submit">Confirmar</button>
      </form>
      </div>
    </div>
  </main>
</body>
</html>
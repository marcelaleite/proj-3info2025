<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Daltonin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/daltonismo.css">
  <style>
    img{
        padding: 50px 50px 0px 50px;
    }
    body{
      color: white;
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

  <main style="padding:80px 20px; min-height:100vh;">
    <div style="max-width:1100px; margin:0 auto; background: rgba(10,10,10,0.55); padding:24px; border-radius:12px;">

    <h2>TESTE DALTONISMO</h2>
    <p>O teste consiste em imagens e opções, selecione o número que você consegue enxergar em cada imagem.</p>
    <center>
    <br>

    <form action="resultado.php" method="post">

      <div>
        <img src="../img/teste1.jpg" height="300px" alt="">
        <br>
        <select name="teste1" id="teste1">
          <option value="2">12</option>
          <option value="2">Doze</option>
        </select>
      </div>    

      <div>
        <img src="../img/teste2.jpg" height="300px" alt="">
        <br>
        <select name="teste2" id="teste2">
          <option value="2">8</option>
          <option value="1">3</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste3.jpg" height="300px" alt="">
        <br>
        <select name="teste3" id="teste3">
          <option value="2">29</option>
          <option value="1">70</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste4.jpg" height="300px" alt="">
        <br>
        <select name="teste4" id="teste4">
          <option value="2">5</option>
          <option value="1">2</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste5.jpg" height="300px" alt="">
        <br>
        <select name="teste5" id="teste5">
          <option value="2">3</option>
          <option value="1">5</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste6.jpg" height="300px" alt="">
        <br>
        <select name="teste6" id="teste6">
          <option value="2">15</option>
          <option value="1">17</option>
          <option value="0">Nada</option>
        </select>
      </div>
    
      <div>
        <img src="../img/teste7.jpg" height="300px" alt="">
        <br>
        <select name="teste7" id="teste7">
          <option value="2">74</option>
          <option value="1">21</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste8.jpg" height="300px" alt="">
        <br>
        <select name="teste8" id="teste8">
          <option value="2">6</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste9.jpg" height="300px" alt="">
        <br>
        <select name="teste9" id="teste9">
          <option value="2">45</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <div>
        <img src="../img/teste10.jpg" height="300px" alt="">
        <br>
        <select name="teste10" id="teste10">
          <option value="2">5</option>
          <option value="0">Nada</option>
        </select>
      </div>

      <br>
      <div>
        <input type="submit" value="Confirmar">
      </div>
    </form>
    </center>
  </main>
  <!---->
</body>
</html>

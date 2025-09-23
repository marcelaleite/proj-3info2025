<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once "Card.php";

$cardObj = new Card();

// Inserir novo card
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = trim($_POST['nome']);
    $imagem = trim($_POST['imagem']);
    $link   = trim($_POST['link']);

    // se não colocar pasta IMG/, adiciona automaticamente
    if (!preg_match("~^IMG/|^img/~i", $imagem)) {
        $imagem = "IMG/" . $imagem;
    }

    $cardObj->criar($nome, $imagem, $link);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Buscar cards
$cards = $cardObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://kit.fontawesome.com/1fa02d05b6.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
  <title>Galeria - Profissionais</title>
  <link rel="stylesheet" href="css/galeria.css">
  <style>
    .preview-box { margin:15px 0; text-align:center; }
    .preview-box img { max-width:200px; max-height:200px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.2); display:none; }
  </style>
</head>
<body>

  <!-- GALERIA FIXA -->
  <div class="container2">
      <div class="card card1">
          <h2>COVINHAS</h2>
          <img src="IMG/COVINHA.PNG" class="prof">
          <button><a href="#">SOBRE</a></button>
      </div>
      <div class="card card2">
          <h2>SARDAS</h2>
          <img src="IMG/SARDAS.PNG" class="prof">
          <button><a href="#">SOBRE</a></button>
      </div>
      <div class="card card1">
          <h2>LÓBULOS DA ORELHA</h2>
          <img src="IMG/LOBULO.webp" class="prof">
          <button><a href="#">SOBRE</a></button>
      </div>
  </div>

  <!-- CARDS DO BANCO -->
  <div class="container2">
      <?php foreach($cards as $i => $card): 
            $tipoCard = ($i % 2 == 0) ? 'card1' : 'card2';
      ?>
        <div class="card <?= $tipoCard ?>">
          <h2><?= htmlspecialchars($card['nome']) ?></h2>
          <img src="<?= htmlspecialchars($card['imagem']) ?>" class="prof">
          <button><a href="<?= htmlspecialchars($card['link']) ?>" target="_blank">SOBRE</a></button>
        </div>
      <?php endforeach; ?>
  </div>

  <!-- FORMULÁRIO COM PREVIEW -->
  <div style="width:100%; max-width:600px; margin:50px auto; text-align:center;">
      <h2>Adicionar novo card</h2>
      <form method="POST">
          <input type="text" name="nome" placeholder="Nome" required><br>
          <input type="text" id="imagem" name="imagem" placeholder="Ex: mulher2.png ou IMG/mulher2.png" required><br>

          <div class="preview-box">
              <img id="preview-img" src="" alt="Preview">
          </div>

          <input type="text" name="link" placeholder="Ex: https://site.com" required><br>
          <button type="submit">Salvar</button>
      </form>
  </div>

  <script>
    document.getElementById("imagem").addEventListener("input", function() {
        let val = this.value.trim();
        if(val !== "") {
            if(!val.match(/^IMG\/|^img\//i)) {
                val = "IMG/" + val;
            }
            const img = document.getElementById("preview-img");
            img.src = val;
            img.style.display = "block";
        }
    });

    VanillaTilt.init(document.querySelectorAll(".card"), {
      max: 25,
      speed: 400,
      glare: true,
      "max-glare":0.5
    });
  </script>

</body>
</html>

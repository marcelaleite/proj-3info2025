<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once(__DIR__ . "/../Model/Card.php");
require_once(__DIR__ . "/CardView.php");

$cardObj = new Card();

// Inserir novo card com upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $link = trim($_POST['link']);

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novoNome = uniqid('img_') . "." . $ext; // nome único

        // Pasta IMG dentro da galeria
        $dir = __DIR__ . "/IMG/";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true); // cria a pasta se não existir
        }

        $destino = $dir . $novoNome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $imagem = "IMG/" . $novoNome;
            $cardObj->criar($nome, $imagem, $link);
        } else {
            die("Erro ao fazer upload da imagem.");
        }
    } else {
        die("Nenhuma imagem enviada ou erro no upload.");
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Buscar cards
$cards = $cardObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Galeria - Profissionais</title>
<script src="https://kit.fontawesome.com/1fa02d05b6.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;300;400;600;700;900&display=swap');

* { margin:0; padding:0; box-sizing:border-box; font-family:'Titillium Web', sans-serif; }
body { background: linear-gradient(to bottom right, #89a7b1, #05a862); min-height:100vh; display:flex; flex-direction:column; align-items:center; color:#fff; }

.container2 { width:100%; max-width:1200px; margin:80px auto; display:flex; flex-wrap:wrap; justify-content:center; gap:40px; }

.card {
  width:300px; height:450px; display:flex; flex-direction:column; align-items:center; justify-content:space-between;
  padding:20px; border-radius:30px; transition: transform 0.3s ease; transform-style: preserve-3d;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.card h2 { text-align:center; color:#fff; font-weight:700; }
.card .prof { max-width:250px; border-radius:15px; transition:0.5s; }

.card:hover .prof,
.card:hover button { transform: translate3d(0,0,50px); }

.card button {
  padding:10px 40px; border:2px solid #fff; border-radius:20px; color:#fff; background:transparent;
  font-size:18px; cursor:pointer; transition:0.5s; position:relative;
}
.card button:hover { color:#000; }
.card button::before {
  content:''; position:absolute; top:0; left:0; width:100%; height:100%;
  background-color:#fff; border-radius:20px; z-index:-1; transform-origin:left; transform:scaleX(0);
  transition: transform 0.5s cubic-bezier(0.5,1.6,0.4,0.7); box-shadow: 0 5px 15px rgba(0,0,0,0.4);
}
.card button:hover::before { transform: scaleX(1); }

.preview-box { margin:15px 0; text-align:center; }
.preview-box img { max-width:200px; max-height:200px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.2); display:none; }

@media (max-width:900px) { .card { width:250px; height:380px; } }
@media (max-width:600px) { .container2 { flex-direction:column; align-items:center; } .card { width:90%; max-width:350px; margin:10px 0; } }
</style>
</head>
<body>

<!-- GALERIA FIXA -->
<div class="container2">
    <div class="card" style="background: linear-gradient(45deg,#04ad04,#00f7ff);">
        <h2>COVINHAS</h2>
        <img src="public/img/COVINHA.PNG" class="prof">
        <button><a href="#">SOBRE</a></button>
    </div>
    <div class="card" style="background: linear-gradient(45deg,#04ad04,#8cdf8c);">
        <h2>SARDAS</h2>
        <img src="public/img/SARDAS.PNG" class="prof">
        <button><a href="#">SOBRE</a></button>
    </div>
    <div class="card" style="background: linear-gradient(45deg,#00f7ff,#05a862);">
        <h2>LÓBULOS DA ORELHA</h2>
        <img src="public/img/LOBULO.webp" class="prof">
        <button><a href="#">SOBRE</a></button>
    </div>
</div>

<!-- CARDS DO BANCO -->
<div class="container2">
    <?php
    $gradientes = [
        "linear-gradient(45deg,#ff416c,#ff4b2b)",
        "linear-gradient(45deg,#1a2a6c,#b21f1f,#fdbb2d)",
        "linear-gradient(45deg,#00c6ff,#0072ff)",
        "linear-gradient(45deg,#f7971e,#ffd200)",
        "linear-gradient(45deg,#00f7ff,#05a862)",
    ];

    foreach($cards as $i => $card):
        $bg = $gradientes[$i % count($gradientes)];
        echo "<div class='card' style='background: $bg; box-shadow:0 15px 35px rgba(0,0,0,0.4);'>";
        $cardView = new CardView($card['nome'], '', $card['imagem'], $card['link']);
        echo $cardView->render(""); // fundo inline já definido
        echo "</div>";
    endforeach;
    ?>
</div>

<!-- FORMULÁRIO COM UPLOAD -->
<div style="width:100%; max-width:600px; margin:50px auto; text-align:center;">
    <h2>Adicionar novo card</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nome" placeholder="Nome" required><br>
        <input type="file" id="imagem" name="imagem" accept="image/*" required><br>
        <div class="preview-box">
            <img id="preview-img" src="" alt="Preview">
        </div>
        <input type="text" name="link" placeholder="Ex: https://site.com" required><br>
        <button type="submit">Salvar</button>
    </form>
</div>

<script>
document.getElementById("imagem").addEventListener("change", function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(event){
            const img = document.getElementById("preview-img");
            img.src = event.target.result;
            img.style.display="block";
        }
        reader.readAsDataURL(file);
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

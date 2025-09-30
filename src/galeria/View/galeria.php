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
        $novoNome = uniqid('img_') . "." . $ext;

        $dir = __DIR__ . "/IMG/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);

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

* {
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Titillium Web', sans-serif;
}

body {
  background: radial-gradient(circle at top left, #0d1b2a, #1b263b, #0f2027);
  min-height:100vh;
  display:flex;
  flex-direction:column;
  align-items:center;
  color:#e0e6ed;
}

/* Container cards */
.container2 {
  width:100%;
  max-width:1200px;
  margin:80px auto;
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:40px;
}

/* Cards */
.card {
  width:300px;
  height:450px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:space-between;
  padding:20px;
  border-radius:20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  transform-style: preserve-3d;

  /* 🔥 Novo esquema de cor */
  background: rgba(20, 25, 40, 0.7); /* tom azul escuro translúcido */
  border: 1px solid rgba(0, 200, 255, 0.25);
  box-shadow: 0 10px 25px rgba(0,0,0,0.6);
  backdrop-filter: blur(12px);
}

.card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 15px 35px rgba(0, 200, 255, 0.4),
              0 0 20px rgba(123, 47, 247, 0.4);
  border-color: rgba(123, 47, 247, 0.6);
}

.card h2 {
  text-align:center;
  color:#fff;
  font-weight:700;
}

.card .prof {
  max-width:250px;
  border-radius:15px;
  transition:0.5s;
}

/* Botão dentro do card */
.sobre-text {
  padding:10px 40px;
  border:2px solid #00f7ff;
  border-radius:20px;
  color:#00f7ff;
  background:transparent;
  font-size:18px;
  font-weight:600;
  transition:0.5s;
  position:relative;
  text-decoration:none;
}

.sobre-text:hover {
  color:#000;
}

.sobre-text::before {
  content:'';
  position:absolute;
  top:0; left:0;
  width:100%; height:100%;
  background:linear-gradient(135deg, #00f7ff, #7b2ff7);
  border-radius:20px;
  z-index:-1;
  transform-origin:left;
  transform:scaleX(0);
  transition: transform 0.5s cubic-bezier(0.5,1.6,0.4,0.7);
  box-shadow: 0 5px 15px rgba(0,0,0,0.6);
}

.sobre-text:hover::before {
  transform: scaleX(1);
}

/* Preview img */
.preview-box {
  margin:15px 0;
  text-align:center;
}
.preview-box img {
  max-width:200px;
  max-height:200px;
  border-radius:10px;
  box-shadow:0 4px 12px rgba(0,0,0,0.4);
  display:none;
}

/* Responsivo */
@media (max-width:900px) {
  .card { width:250px; height:380px; }
}
@media (max-width:600px) {
  .container2 { flex-direction:column; align-items:center; }
  .card { width:90%; max-width:350px; margin:10px 0; }
}

/* Botão flutuante */
#openFormBtn {
  position: fixed;
  top: 20px;
  left: 20px;
  background: rgba(0,255,255,0.1);
  color: #00f7ff;
  border: 1px solid rgba(0,255,255,0.3);
  padding: 12px 14px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.5);
  z-index: 1100;
  transition: 0.3s;
}

#openFormBtn:hover {
  background: linear-gradient(135deg, #00f7ff, #7b2ff7);
  color:#000;
}

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1200;
  top:0; left:0;
  width:100%; height:100%;
  background: rgba(0,0,0,0.7);
  justify-content:center;
  align-items:center;
}

.modal-content {
  background: rgba(255,255,255,0.05);
  padding:30px;
  border-radius:20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.5);
  width:100%; max-width:500px;
  color:#fff;
  backdrop-filter: blur(10px);
  border:1px solid rgba(255,255,255,0.1);
}

.modal-content h2 {
  margin-bottom:20px;
  font-size:26px;
  font-weight:700;
  text-align:center;
}

.modal-content label {
  display:block;
  text-align:left;
  font-weight:600;
  margin-bottom:5px;
}

.modal-content input {
  width:100%;
  padding:12px 15px;
  border:none;
  border-radius:10px;
  outline:none;
  font-size:16px;
  margin-bottom:15px;
  background: rgba(255,255,255,0.1);
  color:#fff;
}

.modal-content button {
  display:block;
  margin: 0 auto;
  padding:12px 40px;
  border:none;
  border-radius:25px;
  background:linear-gradient(135deg, #00f7ff, #7b2ff7);
  color:#000;
  font-weight:700;
  font-size:18px;
  cursor:pointer;
  transition:0.3s;
}

.modal-content button:hover {
  filter: brightness(1.2);
}

.close {
  position:absolute;
  top:15px;
  right:20px;
  font-size:28px;
  color:#fff;
  cursor:pointer;
}

/* Links */
a {
  text-decoration: none;
  color: #00f7ff;
  transition: color 0.5s ease;
}
a:hover {
  color: #7b2ff7;
}

</style>
</head>
<body>

<!-- BOTÃO -->
<button id="openFormBtn"><i class="fas fa-plus"></i></button>

<!-- MODAL -->
<div id="formModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Cadastrar Nova Característica</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Nome da característica</label>
      <input type="text" name="nome" placeholder="Ex: Covinhas" required>
      
      <label>Imagem (upload)</label>
      <input type="file" id="imagem" name="imagem" accept="image/*" required>
      <div class="preview-box"><img id="preview-img" src="" alt="Preview"></div>
      
      <label>Link do site</label>
      <input type="text" name="link" placeholder="Ex: https://site.com" required>
      
      <button type="submit">Salvar</button>
    </form>
  </div>
</div>

<!-- GALERIA FIXA -->
<div class="container2">
  <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>COVINHAS</h2>
    <img src="../../../public/img/covinha.png" class="prof">
    <div class="sobre-text"><a href="https://www.uol.com.br/vivabem/noticias/redacao/2020/11/12/adoravel-fofa-e-charmosa-sabia-que-a-covinha-e-um-defeito-congenito.htm">SOBRE</a></div>
  </div>
  <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>SARDAS</h2>
    <img src="../../../public/img/sardas.png" class="prof">
    <div class="sobre-text"><a href="https://sbdrj.org.br/o-que-sao-e-como-surgem-as-sardas/">SOBRE</a></div>
  </div>
  <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>LÓBULO LIVRE</h2>
    <img src="../../../public/img/lobulol.jpg" class="prof">
    <div class="sobre-text"><a href="https://www.verywellhealth.com/earlobe-anatomy-5092216">SOBRE</a></div>
  </div>
   <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>LÓBULO PRESO</h2>
    <img src="../../../public/img/lobulop.webp" class="prof">
    <div class="sobre-text"><a href="https://www.verywellhealth.com/earlobe-anatomy-5092216">SOBRE</a></div>
  </div>
   <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>ALBINISMO</h2>
    <img src="../../../public/img/albiii.png" class="prof">
    <div class="sobre-text"><a href="https://vidasaudavel.einstein.br/albinismo/">SOBRE</a></div>
  </div>
   <div class="card" style="background: linear-gradient(45deg, rgba(30,30,40,0.95), rgba(60,60,80,0.95));">
    <h2>GRUPO SANGUÍNEO</h2>
    <img src="../../../public/img/sangue.webp" class="prof">
    <div class="sobre-text"><a href="https://www.todamateria.com.br/tipos-sanguineos/">SOBRE</a></div>
  </div>
</div>

<!-- CARDS DO BANCO -->
<div class="container2">
<?php
$gradientes = [
  "linear-gradient(45deg,#04ad04,#00f7ff)",
  "linear-gradient(45deg,#04ad04,#8cdf8c)",
  "linear-gradient(45deg,#00f7ff,#05a862)",
  "linear-gradient(45deg,#ff416c,#ff4b2b)",
  "linear-gradient(45deg,#1a2a6c,#b21f1f,#fdbb2d)",
  "linear-gradient(45deg,#00c6ff,#0072ff)",
  "linear-gradient(45deg,#f7971e,#ffd200)"
];

foreach($cards as $i => $card):
  $bg = $gradientes[$i % count($gradientes)];
?>
 <?php< foreach($cards as $card): ?>
  <div class="card">
    <h2><?= htmlspecialchars($card['nome']) ?></h2>
    <img src="<?= htmlspecialchars($card['imagem']) ?>" class="prof">
    <div class="sobre-text">
      <a href="<?= htmlspecialchars($card['link']) ?>" target="_blank">SOBRE</a>
    </div>
  </div>
<?php endforeach; ?>
</div>

<script>
const modal = document.getElementById("formModal");
const openBtn = document.getElementById("openFormBtn");
const closeBtn = document.querySelector(".close");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }

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
  max: 25, speed: 400, glare: true, "max-glare":0.5
});
</script>

</body>
</html>
<?php
// Fechar conexão se aberta
<?php
include_once '../../../config/config.inc.php'; // aqui já deve ter $conn

$respostas = $_POST;

$soma = 0;
foreach ($respostas as $valor) {
    $soma += (int)$valor;
}

$totalQuestoes = count($respostas);
$soma = $soma / 2;

if ($soma > 6) {
    $daltonismo = "Não";
} else {
    $daltonismo = "Sim";
}

// ============================
// SALVAR NO BANCOasdasd
// ============================

// aqui vou supor que você tem o id do usuário logado em sessão:
session_start();
$idUsuario = $_SESSION['usuario_id'] ?? 1; // se não tiver sessão, usa 1 como teste

// se sua conexão é mysqli
$sql = "UPDATE perfil SET daltonismo = ? WHERE usuario_idusuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $daltonismo, $idUsuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $msg = "Resultado salvo no banco com sucesso!";
} else {
    $msg = "Nenhum dado alterado no banco.";
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <title>Resultado do Teste</title>
</head>
<body>
  <h1>Resultado</h1>
  <p>Pontuação: <strong><?php echo $soma; ?></strong></p>

  <?php
    if ($soma >= 9){
      echo "<p>Você não é daltônico</p>";
    } elseif ($soma < 9 && $soma >= 6){
      echo "<p>Possível daltonismo</p>";
    } elseif ($soma < 6 && $soma >= 3){
      echo "<p>Chance alta de daltonismo</p>";
    } elseif ($soma < 3 && $soma >= 1){
      echo "<p>Daltônico (consulte um oftalmologista)</p>";
    } elseif ($soma < 1){
      echo "<p>Se passou</p>";
    }
  ?>

  <p><?php echo $msg; ?></p>
  <a href="daltonismo.html">Enviar resultado</a>
</body>
</html>

<?php
include_once '../../../config/config.inc.php';

$respostas = $_POST;

$soma = 0;

foreach ($respostas as $valor) {
    $soma += (int)$valor;
}

$totalQuestoes = count($respostas);
$soma = $soma / 2;

if ($soma > 6) {
  $daltonismo = "não";
}else{
  $daltonismo = "sim";
}
//
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
      echo "<p>Você não é daltonico</p>";
    }elseif ($soma < 9 && $soma >= 6){
      echo "<p>Possível daltonismo</p>";
    }elseif ($soma < 6 && $soma >= 3){
      echo "<p>Chance alta de daltonismo</p>";
    }elseif ($soma < 3 && $soma >= 1){
      echo "<p>Daltônico (consulte um oftamologista)</p>";
    }elseif ($soma < 1){
      echo "<p>Se passou</p>";
    }
  ?>
  <a href="daltonismo.html">Enviar resultado</a>
</body>
</html>

<?php 
include_once '../Model/dalto.class.php'; // ajusta o caminho certo aqui

// Se no form você usou name="respostas[]"
$respostas = $_POST['respostas'];

// Gabarito fixo
$fixo = ['12','6','29','5','3','15','74','6','45'];
$a = 0;

// Conta acertos
for ($i=0; $i 
< count($fixo); $i++) { 
  if (isset($respostas[$i]) && trim($respostas[$i]) == $fixo[$i]) {
    $a++;
  }
}
?> 

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <title>Resultado do Teste</title>
</head>
<body>
  <h1>Resultado</h1>
  <p>Pontuação: <strong><?php echo $a; ?>/9</strong></p>

  <?php
    if ($a >= 9){
      echo "<p>Você não é daltônico</p>";
    } elseif ($a >= 6) {
      echo "<p>Possível daltonismo</p>";
    } elseif ($a >= 3) {
      echo "<p>Chance alta de daltonismo</p>";
    } elseif ($a >= 1) {
      echo "<p>Daltônico (consulte um oftalmologista)</p>";
    } else {
      echo "<p>Nenhum número identificado</p>";
    }
  ?>
  <a href="../View/daltonismo.html">Concluir</a>
</body>
</html>

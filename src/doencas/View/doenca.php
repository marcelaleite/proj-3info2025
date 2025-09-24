<?php
session_start();
require_once "../Model/probabildade_doenca_class.php";

if (!isset($_SESSION['doencas'])) {
    $_SESSION['doencas'] = [
        ['nome' => 'Albinismo'],
        ['nome' => 'Daltonismo'],
        ['nome' => 'Hemofilia']
    ];
}

$doencas = $_SESSION['doencas'];

$resultado = "";
$doenca_selecionada = "";
$pai_tem = false;
$mae_tem = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doenca_selecionada = $_POST['doenca'];
    $pai_tem = isset($_POST['pai_tem']);
    $mae_tem = isset($_POST['mae_tem']);

    $probabilidade = new Probabilidade();
    $resultado = $probabilidade->calcular($doenca_selecionada, $pai_tem, $mae_tem);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Simulação de Probabilidade de Doença</title>
    <link rel="stylesheet" href="../../../public/styles.css">
</head>
<body>
    <h2>Simular probabilidade de Doença Genética</h2>
    <form method="POST" action="">
        <label for="doenca">Doença:</label>
        <select name="doenca" id="doenca" required>
            <option value="">Selecione uma doença</option>
            <?php foreach ($doencas as $d): ?>
                <option value="<?= htmlspecialchars($d['nome']) ?>" <?= ($doenca_selecionada == $d['nome']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>
            <input type="checkbox" name="pai_tem" value="1" <?= $pai_tem ? 'checked' : '' ?>>
            O pai tem a doença
        </label><br><br>

        <label>
            <input type="checkbox" name="mae_tem" value="1" <?= $mae_tem ? 'checked' : '' ?>>
            A mãe tem a doença
        </label><br><br>

        <input type="submit" value="Calcular">
    </form>

    <?php if (!empty($resultado)): ?>
        <h3>Resultado da Simulação:</h3>
        <p><?= htmlspecialchars($resultado) ?></p>
    <?php endif; ?>

    <br>
    <a href="cadastro_doenca.html">Cadastrar Nova Doença</a>
</body>
</html>

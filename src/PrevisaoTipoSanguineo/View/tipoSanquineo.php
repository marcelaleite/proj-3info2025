<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Tipo Sanguíneo</title>
</head>
<body>
    <h1>Calculadora de Tipo Sanguíneo</h1>
    <form action="../Control/controle_TipoSanguineo.php" method="post">
        <label for="id_usuario">Selecione o Usuário (Filho):</label>
        <select name="id_usuario" id="id_usuario">
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id_usuario'] ?>" <?= (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $u['id_usuario']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="Calcular">
    </form>

    <?php if ($resultado): ?>
        <hr>
        <h2>Resultados da Previsão</h2>
        <?php if (is_string($resultado)): ?>
            <p><?= htmlspecialchars($resultado) ?></p>
        <?php else: ?>
            <p><strong>Tipo sanguíneo do pai:</strong> <?= htmlspecialchars($resultado['pai']) ?></p>
            <p><strong>Tipo sanguíneo da mãe:</strong> <?= htmlspecialchars($resultado['mae']) ?></p>
            <p><strong>Possíveis tipos sanguíneos do filho:</strong> <?= htmlspecialchars(implode(', ', $resultado['filho_tipos'])) ?></p>
            <p><strong>Possíveis fatores Rh do filho:</strong> <?= htmlspecialchars(implode(', ', $resultado['filho_rh'])) ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
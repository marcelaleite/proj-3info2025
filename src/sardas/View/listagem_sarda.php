<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sistema", "root", "");
    require_once __DIR__ . "/../Control/SardaController.php";
    $controller = new SardaController($pdo);

    $busca = $_GET['busca'] ?? '';
    if ($busca) {
        $stmt = $pdo->prepare("SELECT * FROM sardas WHERE usuario_id LIKE ? OR sarda LIKE ?");
        $stmt->execute(["%$busca%", "%$busca%"]);
        $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $lista = $controller->listarUsuarios();
    }
} catch (PDOException $e) {
    echo '<h2>Erro ao conectar ao banco de dados</h2>';
    echo '<p>Verifique se o MySQL está rodando e se as configurações estão corretas.</p>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
?>

<form method="get" style="margin-bottom:24px;">
    <input type="text" name="busca" placeholder="Buscar por usuário ou resultado" value="<?= htmlspecialchars($busca ?? '') ?>" style="padding:8px 16px; border-radius:8px; border:1px solid #ccc; width:260px;">
    <button type="submit" style="padding:8px 18px; border-radius:8px; font-weight:700; background:#1CECE7; color:#000; border:none; cursor:pointer;">Buscar</button>
</form>
<h2>Perfis cadastrados</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Usuário</th>
        <th>Pai</th>
        <th>Mãe</th>
        <th>Sarda</th>
    </tr>
    <?php foreach ($lista as $linha): ?>
    <tr>
        <td><?= $linha['id_perfil'] ?></td>
        <td><?= $linha['usuario_id'] ?></td>
        <td><?= $linha['id_pai'] ?></td>
        <td><?= $linha['id_mae'] ?></td>
        <td><?= $linha['sarda'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php 
use App\Covinhas\Controller\CovinhaController;
use App\Covinhas\DAO\CovinhaDAO;


$host = "localhost";
$db   = "biolineage";   
$user = "root";            
$pass = "";                

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco: " . $e->getMessage());
}


require_once __DIR__ . '/../DAO/CovinhaDAO.class.php';
require_once __DIR__ . '/../Control/CovinhaController.php';

$controller = new CovinhaController($pdo);
$dao = new CovinhaDAO($pdo);
$covinhasCompletas = $dao->listarPerfisComUsuarios();
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Covinhas — Hereditariedade</title>
<link rel="stylesheet" href="../../../public/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style> 
  body { font-family: Arial, sans-serif; background-color: #f7f7f7; color: #333; margin:0; padding:0; } 
  a { color: #007BFF; text-decoration: none; } 
  a:hover { text-decoration: underline; } 
  .topbar { background-color: #222; padding: 10px 20px; } 
  .topbar a { color: #fff; margin-right: 15px; } 
  .menu-card { max-width: 900px; margin: 30px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow:0px 4px 10px rgba(0,0,0,0.1);} 
  h2 { margin-top:0; font-size:1.5em; } 
  table { width:100%; border-collapse:collapse; margin-top:20px; background-color:#fff;} 
  th, td { border:1px solid #ddd; padding:8px; text-align:left; } 
  th { background-color:#f2f2f2; } 
</style>
</head>
<body id="page-menu">
<nav class="topbar">
  <div class="nav-inner">
    <a class="nav-link" href="../../../index.php">Início</a>
    <a class="nav-link" href="#">Módulo: Covinhas</a>
  </div>
</nav>

<section class="menu-screen">
<div class="menu-card">
  <div class="menu-header">
    <h2>Listagem de Perfis — Covinhas</h2>
    <p>Aqui estão os perfis cadastrados com suas características e vínculos familiares.</p>
  </div>

  <div class="table-wrap">
    <table class="list">
      <thead>
        <tr>
          <th>ID Perfil</th>
          <th>Usuário</th>
          <th>C. Queixo</th>
          <th>C. Bochecha</th>
          <th>Pai</th>
          <th>Mãe</th>
          <th>Resultado</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($covinhasCompletas)): ?>
          <?php foreach ($covinhasCompletas as $covinha): ?>
            <tr>
              <td><?= (int)$covinha['id_perfil'] ?></td>
              <td><?= htmlspecialchars($covinha['usuario_nome']) ?></td>
              <td><?= $covinha['cov_queixo'] === null ? '—' : ($covinha['cov_queixo'] ? 'com' : 'sem') ?></td>
              <td><?= $covinha['cov_bochecha'] === null ? '—' : ($covinha['cov_bochecha'] ? 'com' : 'sem') ?></td>
              <td><?= htmlspecialchars($covinha['pai_nome'] ?? $covinha['pai'] ?? '—') ?></td>
              <td><?= htmlspecialchars($covinha['mae_nome'] ?? $covinha['mae'] ?? '—') ?></td>
              <td><?= htmlspecialchars($covinha['resultado'] ?? '—') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align:center;">Nenhum perfil encontrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
<?php
if (count($covinhasCompletas) >= 2) {
    $paiId = $covinhasCompletas[0]['id_perfil'];
    $maeId = $covinhasCompletas[1]['id_perfil'];
    $resultado = $controller->calcular($paiId, $maeId);
?>
  <div class="menu-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Resultado das Probabilidades</h2>
      <p>Baseado nos dois primeiros perfis listados.</p>
    </div>

    <!-- Tabela Queixo -->
    <h3>Queixo</h3>
    <table class="list">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>CC</th>
          <th>Cc</th>
          <th>cc</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Genótipos</td>
          <td><?= $resultado['queixo']['genotipos']['CC'] ?? '—' ?>%</td>
          <td><?= $resultado['queixo']['genotipos']['Cc'] ?? '—' ?>%</td>
          <td><?= $resultado['queixo']['genotipos']['cc'] ?? '—' ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com: <?= $resultado['queixo']['fenotipos']['com'] ?? '—' ?>% |
            Sem: <?= $resultado['queixo']['fenotipos']['sem'] ?? '—' ?>%
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Tabela Bochecha -->
    <h3 style="margin-top:20px;">Bochecha</h3>
    <table class="list">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>CC</th>
          <th>Cc</th>
          <th>cc</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Genótipos</td>
          <td><?= $resultado['bochecha']['genotipos']['CC'] ?? '—' ?>%</td>
          <td><?= $resultado['bochecha']['genotipos']['Cc'] ?? '—' ?>%</td>
          <td><?= $resultado['bochecha']['genotipos']['cc'] ?? '—' ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com: <?= $resultado['bochecha']['fenotipos']['com'] ?? '—' ?>% |
            Sem: <?= $resultado['bochecha']['fenotipos']['sem'] ?? '—' ?>%
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<?php } ?>

</div>
</section>
</body>
</html>

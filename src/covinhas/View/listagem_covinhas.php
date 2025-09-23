<?php 
use App\Covinhas\Controller\CovinhaController;

require_once __DIR__ . '/../../../config/config.inc.php';
require_once __DIR__ . '/../DAO/CovinhaDAO.class.php';
require_once __DIR__ . '/../Control/CovinhaController.php';
require_once('../valida_login.php');

$controller = new CovinhaController($pdo);
$dao = new \App\Covinhas\DAO\CovinhaDAO($pdo);
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

</div>
</section>
</body>
</html>

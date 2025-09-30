<?php 
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header("Location: ../../cadastro_menu_login/Control/index.php");
    exit;
}

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

// OBTER USUÁRIO LOGADO
$usuarioLogadoId = $_SESSION['id_usuario'];
$perfilLogado = null;
$resultado = null;

// Encontrar perfil do usuário logado
foreach ($covinhasCompletas as $perfil) {
    if ($perfil['usuario_idusuario'] == $usuarioLogadoId) {
        $perfilLogado = $perfil;
        break;
    }
}

// SE ENCONTRAR O PERFIL E ELE TIVER AMBOS OS PAIS CADASTRADOS, FAZER O CÁLCULO
if ($perfilLogado && !empty($perfilLogado['id_pai']) && !empty($perfilLogado['id_mae'])) {
    $paiId = $perfilLogado['id_pai'];
    $maeId = $perfilLogado['id_mae'];
    
    // Buscar os perfis dos pais
    $perfilPai = $dao->getPerfilByUsuario($paiId);
    $perfilMae = $dao->getPerfilByUsuario($maeId);
    
    if ($perfilPai && $perfilMae) {
        $resultado = $controller->calcular($paiId, $maeId);
    }
}
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
  .user-info { color: #fff; margin-left: auto; }
  .warning-card { background: #fff3cd; border: 1px solid #ffeaa7; }
  .success-card { background: #d4edda; border: 1px solid #c3e6cb; }
</style>
</head>
<body id="page-menu">
<nav class="topbar">
  <div class="nav-inner" style="display: flex; align-items: center;">
    <a class="nav-link" href="../../../index.php">Início</a>
    <a class="nav-link" href="#">Módulo: Covinhas</a>
    <?php if ($perfilLogado): ?>
      <span class="user-info">
        Logado como: <?= htmlspecialchars($perfilLogado['usuario_nome']) ?>
      </span>
    <?php endif; ?>
  </div>
</nav>

<section class="menu-screen">
<div class="menu-card">
  <div class="menu-header">
    <h2>Listagem de Perfis — Covinhas</h2>
    <p>Aqui estão os perfis cadastrados com suas características e vínculos familiares.</p>
    <?php if ($perfilLogado): ?>
      <p><strong>Usuário logado:</strong> <?= htmlspecialchars($perfilLogado['usuario_nome']) ?></p>
      <?php if ($perfilLogado['id_pai'] && $perfilLogado['id_mae']): ?>
        <p><strong>Pais cadastrados:</strong> Sim</p>
      <?php else: ?>
        <p><strong>Pais cadastrados:</strong> Não (é necessário cadastrar ambos os pais para ver os cálculos)</p>
      <?php endif; ?>
    <?php endif; ?>
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
              <td>
                <?= htmlspecialchars($covinha['usuario_nome']) ?>
                <?php if ($covinha['usuario_idusuario'] == $usuarioLogadoId): ?>
                  <strong> (Você)</strong>
                <?php endif; ?>
              </td>
              <td><?= $covinha['cov_queixo'] === null ? '—' : ($covinha['cov_queixo'] ? 'com' : 'sem') ?></td>
              <td><?= $covinha['cov_bochecha'] === null ? '—' : ($covinha['cov_bochecha'] ? 'com' : 'sem') ?></td>
              <td><?= htmlspecialchars($covinha['pai_nome'] ?? '—') ?></td>
              <td><?= htmlspecialchars($covinha['mae_nome'] ?? '—') ?></td>
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

<?php if ($resultado && !isset($resultado['erro'])): ?>
  <div class="menu-card success-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Resultado das Probabilidades - Seus Pais</h2>
      <p>Baseado nos pais cadastrados no seu perfil.</p>
    </div>

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
          <td><?= number_format(($resultado['queixo']['filhoGen']['CC'] ?? 0) * 100, 1) ?>%</td>
          <td><?= number_format(($resultado['queixo']['filhoGen']['Cc'] ?? 0) * 100, 1) ?>%</td>
          <td><?= number_format(($resultado['queixo']['filhoGen']['cc'] ?? 0) * 100, 1) ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com: <?= number_format(($resultado['queixo']['filhoFen']['sim'] ?? 0) * 100, 1) ?>% |
            Sem: <?= number_format(($resultado['queixo']['filhoFen']['nao'] ?? 0) * 100, 1) ?>%
          </td>
        </tr>
      </tbody>
    </table>

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
          <td><?= number_format(($resultado['bochecha']['filhoGen']['CC'] ?? 0) * 100, 1) ?>%</td>
          <td><?= number_format(($resultado['bochecha']['filhoGen']['Cc'] ?? 0) * 100, 1) ?>%</td>
          <td><?= number_format(($resultado['bochecha']['filhoGen']['cc'] ?? 0) * 100, 1) ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com: <?= number_format(($resultado['bochecha']['filhoFen']['sim'] ?? 0) * 100, 1) ?>% |
            Sem: <?= number_format(($resultado['bochecha']['filhoFen']['nao'] ?? 0) * 100, 1) ?>%
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<?php elseif ($resultado && isset($resultado['erro'])): ?>
  <div class="menu-card warning-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Erro no Cálculo</h2>
      <p><?= htmlspecialchars($resultado['erro']) ?></p>
    </div>
  </div>
<?php elseif ($perfilLogado && (empty($perfilLogado['id_pai']) || empty($perfilLogado['id_mae']))): ?>
  <div class="menu-card warning-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Aviso</h2>
      <p>Para ver os cálculos de probabilidade, você precisa ter ambos os pais (pai e mãe) cadastrados no seu perfil.</p>
    </div>
  </div>
<?php endif; ?>

</div>
</section>
</body>
</html>
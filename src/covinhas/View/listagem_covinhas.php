<?php
use App\Covinhas\Controller\CovinhaController;

require_once __DIR__ . '/../../../config/config.inc.php';
require_once __DIR__ . '/../DAO/CovinhaDAO.class.php';
require_once __DIR__ . '/../Control/CovinhaController.php';

$controller = new CovinhaController($pdo);

$usuarios = $controller->listarUsuarios();
$tabela = $controller->listarTabela();

$resultado = null;
$paiSel = isset($_GET['pai']) ? (int)$_GET['pai'] : null;
$maeSel = isset($_GET['mae']) ? (int)$_GET['mae'] : null;

if ($paiSel && $maeSel) {
    $resultado = $controller->calcular($paiSel, $maeSel);
}

function nomeUsuario($users, $id) {
    foreach ($users as $u) if ((int)$u['id_usuario'] === (int)$id) return $u['nome'];
    return '—';
}

function fmtPct($p) {
    if ($p === null) return '—';
    return number_format($p*100, 1, ',', '.') . '%';
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
a { color: #007BFF; text-decoration: none; } a:hover { text-decoration: underline; }
.topbar { background-color: #222; padding: 10px 20px; } .topbar a { color: #fff; margin-right: 15px; }
.menu-card { max-width: 900px; margin: 30px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow:0px 4px 10px rgba(0,0,0,0.1);}
h2 { margin-top:0; font-size:1.5em; }
form select, form button { padding:8px 10px; margin:5px 0; border-radius:5px; border:1px solid #ccc; font-size:1em;}
form button { background-color:#007BFF; color:white; border:none; cursor:pointer;}
form button:hover { background-color:#0056b3;}
table { width:100%; border-collapse:collapse; margin-top:20px; background-color:#fff;}
th, td { border:1px solid #ddd; padding:8px; text-align:left; }
th { background-color:#f2f2f2; }
.pill { padding:4px 8px; border-radius:12px; font-size:0.9em; display:inline-block; }
.pill-yes { background-color:#c8f7c5; color:#2c662d; }
.pill-no { background-color:#f7c5c5; color:#662c2c; }
.result-card { background:#fafafa; padding:15px; border-radius:6px; margin-bottom:20px; }
.mono { font-family: monospace; }
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
    <h2>Hereditariedade — Covinhas (Queixo e Bochecha)</h2>
    <p>Selecione Pai e Mãe para estimar as probabilidades do bebê apresentar covinhas.</p>
  </div>

  <form method="get" class="form-card" style="margin-bottom:18px;">
    <div class="grid2">
      <div>
        <label class="row">
          <span style="display:block; font-weight:600; font-size:13px; margin-bottom:6px; color:#333;">Pai</span>
          <select name="pai" required style="width:100%; padding:10px 12px; border-radius:8px;">
            <option value="">Selecione o pai…</option>
            <?php foreach ($usuarios as $u): ?>
              <option value="<?= (int)$u['id_usuario'] ?>" <?= $paiSel === (int)$u['id_usuario'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
      <div>
        <label class="row">
          <span style="display:block; font-weight:600; font-size:13px; margin-bottom:6px; color:#333;">Mãe</span>
          <select name="mae" required style="width:100%; padding:10px 12px; border-radius:8px;">
            <option value="">Selecione a mãe…</option>
            <?php foreach ($usuarios as $u): ?>
              <option value="<?= (int)$u['id_usuario'] ?>" <?= $maeSel === (int)$u['id_usuario'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn primary">Calcular</button>
    </div>
  </form>

  <?php if ($resultado && empty($resultado['erro'])): ?>
    <div class="result-card">
      <h3>Resultados para: <span class="mono"><?= htmlspecialchars(nomeUsuario($usuarios, $paiSel)) ?></span> × <span class="mono"><?= htmlspecialchars(nomeUsuario($usuarios, $maeSel)) ?></span></h3>

      <?php foreach (['queixo', 'bochecha'] as $campo): ?>
        <h4><?= ucfirst($campo) ?></h4>
        <p>Distribuição do Pai: 
          CC: <?= fmtPct($resultado[$campo]['distPai']['CC']) ?>, 
          Cc: <?= fmtPct($resultado[$campo]['distPai']['Cc']) ?>, 
          cc: <?= fmtPct($resultado[$campo]['distPai']['cc']) ?>
        </p>
        <p>Distribuição da Mãe: 
          CC: <?= fmtPct($resultado[$campo]['distMae']['CC']) ?>, 
          Cc: <?= fmtPct($resultado[$campo]['distMae']['Cc']) ?>, 
          cc: <?= fmtPct($resultado[$campo]['distMae']['cc']) ?>
        </p>
        <p>Filho (Genótipo): 
          CC: <?= fmtPct($resultado[$campo]['filhoGen']['CC']) ?>, 
          Cc: <?= fmtPct($resultado[$campo]['filhoGen']['Cc']) ?>, 
          cc: <?= fmtPct($resultado[$campo]['filhoGen']['cc']) ?>
        </p>
        <p>Filho (Fenótipo): 
          <span class="pill pill-yes">Sim: <?= fmtPct($resultado[$campo]['filhoFen']['sim']) ?></span>
          <span class="pill pill-no">Não: <?= fmtPct($resultado[$campo]['filhoFen']['nao']) ?></span>
        </p>
      <?php endforeach; ?>
    </div>
  <?php elseif ($resultado && !empty($resultado['erro'])): ?>
    <div class="result-card">
      <p><?= htmlspecialchars($resultado['erro']) ?></p>
    </div>
  <?php endif; ?>

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
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tabela as $row): ?>
          <tr>
            <td><?= (int)$row['id_perfil'] ?></td>
            <td><?= htmlspecialchars($row['usuario_nome']) ?></td>
            <td>
              <?php if ($row['cov_queixo'] === null): ?>
                <span class="pill">—</span>
              <?php elseif ((int)$row['cov_queixo'] === 1): ?>
                <span class="pill pill-yes">com</span>
              <?php else: ?>
                <span class="pill pill-no">sem</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['cov_bochecha'] === null): ?>
                <span class="pill">—</span>
              <?php elseif ((int)$row['cov_bochecha'] === 1): ?>
                <span class="pill pill-yes">com</span>
              <?php else: ?>
                <span class="pill pill-no">sem</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['pai_nome'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['mae_nome'] ?? '—') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</section>
</body>
</html>

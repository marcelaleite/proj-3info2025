<?php
use App\Albinismo\Control\Controle;

require_once __DIR__ . '/../../../config/config.inc.php';
require_once __DIR__ . '/../Model/AlbinismoDAO.class.php';
require_once __DIR__ . '/../Control/controle.php';

$controller = new Controle($pdo);

$usuarios = $controller->listarUsuarios();
$tabela   = $controller->listarTabela();

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
<title>Albinismo — Hereditariedade</title>
<link rel="stylesheet" href="../../../public/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: Arial, sans-serif; background:#f7f7f7; margin:0; padding:0; }
.topbar { background:#222; padding:12px 20px; }
.topbar a { color:#fff; margin-right:15px; text-decoration:none; }
.menu-card { max-width:900px; margin:30px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
h2 { margin-top:0; }
form select, form button { padding:8px 10px; margin:5px 0; border-radius:5px; border:1px solid #ccc; font-size:1em; }
form button { background:#007BFF; color:white; border:none; cursor:pointer; }
form button:hover { background:#0056b3; }
.pill { padding:4px 8px; border-radius:12px; font-size:0.9em; display:inline-block; }
.pill-yes { background:#c8f7c5; color:#2c662d; }
.pill-no { background:#f7c5c5; color:#662c2c; }
.result-card { background:#fafafa; padding:15px; border-radius:6px; margin-bottom:20px; }
.mono { font-family: monospace; }
</style>
</head>
<body>
<nav class="topbar">
  <a href="../../../index.php">Início</a>
  <a href="#">Módulo: Albinismo</a>
</nav>

<section>
<div class="menu-card">
  <h2>Hereditariedade — Albinismo</h2>
  <p>Selecione Pai e Mãe para estimar as probabilidades do bebê apresentar <strong>albinismo</strong>.</p>

  <form method="get" style="margin-bottom:18px;">
    <label>Pai:
      <select name="pai" required>
        <option value="">Selecione o pai…</option>
        <?php foreach ($usuarios as $u): ?>
          <option value="<?= (int)$u['id_usuario'] ?>" <?= $paiSel === (int)$u['id_usuario'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Mãe:
      <select name="mae" required>
        <option value="">Selecione a mãe…</option>
        <?php foreach ($usuarios as $u): ?>
          <option value="<?= (int)$u['id_usuario'] ?>" <?= $maeSel === (int)$u['id_usuario'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <button type="submit">Calcular</button>
  </form>

  <?php if ($resultado && empty($resultado['erro'])): ?>
    <div class="result-card">
      <h3>Resultados para: 
        <span class="mono"><?= htmlspecialchars(nomeUsuario($usuarios, $paiSel)) ?></span> × 
        <span class="mono"><?= htmlspecialchars(nomeUsuario($usuarios, $maeSel)) ?></span>
      </h3>

      <p>Distribuição do Pai: 
        AA: <?= fmtPct($resultado['pai']['AA']) ?>, 
        Aa: <?= fmtPct($resultado['pai']['Aa']) ?>, 
        aa: <?= fmtPct($resultado['pai']['aa']) ?>
      </p>
      <p>Distribuição da Mãe: 
        AA: <?= fmtPct($resultado['mae']['AA']) ?>, 
        Aa: <?= fmtPct($resultado['mae']['Aa']) ?>, 
        aa: <?= fmtPct($resultado['mae']['aa']) ?>
      </p>
      <p>Filho (Genótipo): 
        AA: <?= fmtPct($resultado['filho']['AA']) ?>, 
        Aa: <?= fmtPct($resultado['filho']['Aa']) ?>, 
        aa: <?= fmtPct($resultado['filho']['aa']) ?>
      </p>
      <p>Filho (Fenótipo): 
        <span class="pill pill-yes">Albino: <?= fmtPct($resultado['fenotipo']['albino']) ?></span>
        <span class="pill pill-no">Normal: <?= fmtPct($resultado['fenotipo']['normal']) ?></span>
      </p>
    </div>
  <?php elseif ($resultado && !empty($resultado['erro'])): ?>
    <div class="result-card">
      <p><?= htmlspecialchars($resultado['erro']) ?></p>
    </div>
  <?php endif; ?>

  <h3>Tabela de Usuários</h3>
  <table>
    <thead>
      <tr>
        <th>ID Perfil</th>
        <th>Usuário</th>
        <th>Albinismo</th>
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
            <?php if ($row['albinismo'] === null): ?>
              <span class="pill">—</span>
            <?php elseif ((int)$row['albinismo'] === 1): ?>
              <span class="pill pill-yes">Sim</span>
            <?php else: ?>
              <span class="pill pill-no">Não</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['pai_nome'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['mae_nome'] ?? '—') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</section>
</body>
</html>

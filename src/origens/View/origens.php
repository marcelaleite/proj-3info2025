<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$possibleDbPaths = [
    __DIR__ . '/../../DAO/Database.class.php',        
    __DIR__ . '/../DAO/Database.class.php',
    __DIR__ . '/../../../src/DAO/Database.class.php',
];

$dbIncluded = false;
foreach ($possibleDbPaths as $p) {
    if (file_exists($p)) {
        require_once $p;
        $dbIncluded = true;
        break;
    }
}
if (!$dbIncluded) {
    die("Database.class.php não encontrado. Procurei em:\n" . implode("\n", $possibleDbPaths));
}

function dbFetch($sql, $params = []) {
    $stmt = Database::executar($sql, $params);
    if ($stmt === false) return false;
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function dbFetchAll($sql, $params = []) {
    $stmt = Database::executar($sql, $params);
    if ($stmt === false) return [];
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

define('MAX_GEN', 6); 
$uid = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$treeNodes = [];   
$visited = [];     
$aggregate = [];  
$totalWeight = 0.0;
$usedNodesCount = 0;
$error = null;

function traverseAncestors($userId, $generation = 1) {
    global $treeNodes, $visited, $aggregate, $totalWeight, $usedNodesCount;

    if ($userId <= 0) return;
    if ($generation > MAX_GEN) return;
    if (isset($visited[$userId])) return; 
    $visited[$userId] = true;

    $profile = dbFetch("SELECT usuario_idusuario, id_pai, id_mae, nacionalidade FROM perfil WHERE usuario_idusuario = :uid LIMIT 1", [':uid' => $userId]);

    $userRow = dbFetch("SELECT id_usuario, nome FROM usuario WHERE id_usuario = :uid LIMIT 1", [':uid' => $userId]);

    $node = [
        'id' => $userId,
        'nome' => $userRow ? $userRow['nome'] : null,
        'nacionalidade' => $profile && trim($profile['nacionalidade'] ?? '') !== '' ? $profile['nacionalidade'] : null,
        'generation' => $generation,
        'id_pai' => $profile ? (int)$profile['id_pai'] : null,
        'id_mae' => $profile ? (int)$profile['id_mae'] : null,
    ];

    $treeNodes[] = $node;
    $usedNodesCount++;

    if ($node['nacionalidade']) {
        $weight = pow(0.5, $generation); 
        if (!isset($aggregate[$node['nacionalidade']])) $aggregate[$node['nacionalidade']] = 0.0;
        $aggregate[$node['nacionalidade']] += $weight;
        $totalWeight += $weight;
    }

    if ($node['id_pai'] && $node['id_pai'] !== 0) traverseAncestors($node['id_pai'], $generation + 1);
    if ($node['id_mae'] && $node['id_mae'] !== 0) traverseAncestors($node['id_mae'], $generation + 1);
}

if ($uid > 0) {
    $exists = dbFetch("SELECT id_usuario, nome FROM usuario WHERE id_usuario = :uid LIMIT 1", [':uid' => $uid]);
    if (!$exists) {
        $error = "Usuário com user_id = {$uid} não encontrado.";
    } else {
        $perfilUsuario = dbFetch("SELECT id_pai, id_mae, nacionalidade FROM perfil WHERE usuario_idusuario = :uid LIMIT 1", [':uid' => $uid]);

        if ($perfilUsuario) {
            $pai = (int)$perfilUsuario['id_pai'];
            $mae = (int)$perfilUsuario['id_mae'];

            if (($pai <= 0 && $mae <= 0) && (!isset($perfilUsuario['nacionalidade']) || trim($perfilUsuario['nacionalidade']) === '')) {
                $error = "Nenhum ancestral encontrado (id_pai/id_mae ausentes) e sem nacionalidade no perfil do usuário.";
            } else {
                if ($pai > 0) traverseAncestors($pai, 1);
                if ($mae > 0) traverseAncestors($mae, 1);

                if ($totalWeight == 0.0) {
                    if (isset($perfilUsuario['nacionalidade']) && trim($perfilUsuario['nacionalidade']) !== '') {
                        $aggregate[$perfilUsuario['nacionalidade']] = 1.0; 
                        $totalWeight = 1.0;
                        $treeNodes[] = [
                            'id' => $uid,
                            'nome' => $exists['nome'] ?? null,
                            'nacionalidade' => $perfilUsuario['nacionalidade'],
                            'generation' => 0,
                            'id_pai' => null,
                            'id_mae' => null,
                        ];
                    } else {
                        $error = "Nenhuma nacionalidade encontrada nos ancestrais, nem nacionalidade do próprio perfil.";
                    }
                }
            }
        } else {
            $error = "Perfil do usuário não encontrado. Para calcular ancestrais é preciso que exista um registro em `perfil` ligado ao usuário.";
        }
    }
}

$percentages = [];
if (!$error && $totalWeight > 0) {
    foreach ($aggregate as $country => $w) {
        $percentages[$country] = ($w / $totalWeight) * 100.0;
    }
    arsort($percentages);
}

function renderTreeHTML($nodes) {
    $html = '<div class="tree-raw" style="margin-top:12px;">';
    $html .= '<h4>Árvore de ancestrais (visual)</h4>';
    $html .= '<div style="font-size:13px; color:#dfeff1;">';
    usort($nodes, function($a,$b){ return $a['generation'] <=> $b['generation']; });
    foreach ($nodes as $n) {
        $gen = (int)$n['generation'];
        $indent = max(0, $gen - 1) * 18;
        $name = $n['nome'] ? htmlspecialchars($n['nome']) : "Usuário #".htmlspecialchars($n['id']);
        $nat = $n['nacionalidade'] ? htmlspecialchars($n['nacionalidade']) : '<span style="opacity:0.7">— não informado —</span>';
        $html .= "<div style='padding-left:{$indent}px; margin-bottom:8px; display:flex; gap:10px; align-items:center;'>";
        $html .= "<div style='min-width:220px'><strong>{$name}</strong></div>";
        $html .= "<div style='width:160px'>Nacionalidade: {$nat}</div>";
        $html .= "<div style='color:#9bd; font-size:13px;'>Geração: ".($gen===0 ? 'º (próprio)' : $gen)."</div>";
        $html .= "</div>";
    }
    $html .= '</div></div>';
    return $html;
}

?><!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Origens — Simulador Multigeracional</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/styles.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
  <style>
    :root{
      --bg-900: #071018; 
      --card-bg: rgba(12,16,20,0.64);
      --card-glow: rgba(255,255,255,0.03);
      --muted: #dfeff1;
      --muted-2: #aebfc6;
      --accent: #c98a58; 
      --accent-2: #8b5e3c;
      --glass-border: rgba(255,255,255,0.06);
      --radius: 12px;
      --shadow: 0 10px 30px rgba(2,6,10,0.6);
    }

    *{box-sizing:border-box;margin:0;padding:0}
    html,body,#page-module-template{height:100%}
    body{
      font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      background: var(--bg-900);
      color:var(--muted);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      line-height:1.45;
    }

    .background-video{
      position:fixed; inset:0; z-index:0; overflow:hidden; pointer-events:none;
    }
    .background-video video{
      width:100%; height:100%; object-fit:cover; display:block;
      filter: blur(2px) brightness(0.45) saturate(0.95);
      transform: scale(1.03);
    }

    .overlay{
      position:fixed; inset:0; z-index:1; pointer-events:none;
      background: linear-gradient(180deg, rgba(6,10,14,0.28) 0%, rgba(4,8,12,0.7) 75%);
    }

    main{
      position:relative; z-index:2; min-height:100vh; display:flex; align-items:flex-start; justify-content:center;
      padding:48px 20px 120px; 
    }

    .orig-card{
      width:100%; max-width:1100px; margin:40px auto; padding:26px; border-radius:var(--radius);
      background: linear-gradient(180deg, rgba(18,22,26,0.62), rgba(8,10,12,0.64));
      border: 1px solid var(--glass-border);
      box-shadow: var(--shadow);
      backdrop-filter: blur(8px) saturate(1.06);
    }

    .orig-card h2{ font-size:22px; margin-bottom:6px; color:#fff; letter-spacing:0.2px }
    .orig-card .subtitle{ color:var(--muted-2); font-size:14px; margin-bottom:14px }

    .controls{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    .controls input[name="user_id"]{
      min-width:140px; max-width:260px; padding:10px 12px; border-radius:10px;
      border:1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.02);
      color:var(--muted); outline:none; transition:box-shadow 160ms, transform 120ms;
    }
    .controls input::placeholder{ color: rgba(223,239,241,0.45) }
    .controls input:focus{ box-shadow: 0 6px 18px rgba(137,92,63,0.08); transform:translateY(-1px) }

    .btn{ padding:9px 12px; border-radius:10px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px; text-decoration:none }
    .btn:focus{ outline:2px solid rgba(201,138,90,0.18); outline-offset:2px }

    .btn.primary{
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color:#fff; border:none; box-shadow: 0 8px 22px rgba(201,138,90,0.14);
    }
    .btn.primary:hover{ transform:translateY(-2px) }

    .btn.ghost{
      background:transparent; color:var(--muted); border:1px solid rgba(255,255,255,0.06)
    }
    .btn.ghost:hover{ background: rgba(255,255,255,0.02); transform:translateY(-1px) }

    .meta{ color:var(--muted-2); margin-top:10px; font-size:14px }

    .country-item{
      display:flex; justify-content:space-between; align-items:center; padding:10px 12px; border-radius:10px;
      background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00));
      border:1px solid rgba(255,255,255,0.02); margin-bottom:8px; font-size:14px; color:var(--muted);
    }
    .country-item div:first-child{ max-width:68% ; overflow:hidden; text-overflow:ellipsis; white-space:nowrap }
    .country-item div:last-child{ font-weight:700 }

    .tree-raw{ margin-top:12px; padding:12px; border-radius:10px; background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00)); border:1px solid rgba(255,255,255,0.02) }
    .tree-raw h4{ margin-bottom:8px; font-size:15px }
    .tree-raw > div{ max-height:320px; overflow:auto; padding-right:6px }
    .tree-raw div::-webkit-scrollbar{ width:8px }
    .tree-raw div::-webkit-scrollbar-thumb{ background: rgba(255,255,255,0.03); border-radius:6px }
    .tree-raw strong{ color:#fff }

    .orig-chart-wrap{
      width:360px;
      max-width:100%;
      height:360px;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:12px;
      margin:0 auto;
    }
    #pie{
      width:100% !important;
      height:100% !important;
      border-radius:50%;
      background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00));
      box-shadow: 0 8px 26px rgba(2,6,10,0.45);
      display:block;
    }

    [role="alert"]{ padding:10px 12px; border-radius:8px; background: linear-gradient(90deg, rgba(255,20,20,0.06), rgba(255,255,255,0.01)); color:#ffd1d1 }

    @media (max-width:920px){
      .orig-card{ margin:18px 12px; padding:20px }
      main{ padding-top:28px }
      .orig-card h2{ font-size:20px }
      .country-item{ font-size:13px }
      .orig-chart-wrap{ height:340px }
    }

    @media (max-width:640px){
      .controls{ gap:8px }
      .controls input[name="user_id"]{ width:100%; flex:1 1 auto }
      .orig-card{ padding:18px }
      .tree-raw > div{ max-height:220px }
      .orig-card > div > div{ flex-direction:column }
      .orig-chart-wrap{ width:100%; height:300px }
    }

    .country-item, .btn, .controls input, .orig-card{ transition: all 140ms ease }

    .text-muted{ color:var(--muted-2) }
    .small{ font-size:13px }
  </style>
</head>
<body id="page-module-template">

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
    </video>
  </div>
  <div class="overlay" aria-hidden="true"></div>

  <main>
    <div class="orig-card">
      <h2>Origens — cálculo multigeracional</h2>
      <p class="subtitle">Informe o <strong>user_id</strong> do usuário cujas origens você quer analisar. O sistema sobe até <strong><?= MAX_GEN ?></strong> gerações por padrão (pais → avós → bisavós ...).</p>

      <form method="get" class="controls" style="align-items:center;">
        <input name="user_id" placeholder="user_id (ex: 1)" value="<?= htmlspecialchars($uid > 0 ? $uid : '') ?>" style="padding:10px 12px; border-radius:8px;"/>
        <button class="btn primary" type="submit">Calcular</button>
        <a href="/public/menu.html" class="btn ghost">Voltar</a>
      </form>

      <?php if ($uid <= 0): ?>
        <div class="meta">Coloque um <strong>user_id</strong> e clique em <em>Calcular</em>.</div>
      <?php else: ?>
        <?php if ($error): ?>
          <div role="alert" style="color:#ffd1d1;"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
          <div style="display:flex; gap:20px; flex-wrap:wrap;">
            <div style="flex:1 1 420px;">
              <h3>Porcentagem estimada por nacionalidade</h3>
              <div class="meta">Baseado em <strong><?= $usedNodesCount ?></strong> nó(s) analisado(s) — pesos por geração (pais: 50%, avós: 25%, ...).</div>
              <div style="margin-top:12px;">
                <?php foreach ($percentages as $country => $pct): ?>
                  <div class="country-item"><div><?= htmlspecialchars($country) ?></div><div><?= number_format((float)$pct, 2, ',', '.') ?>%</div></div>
                <?php endforeach; ?>
              </div>

              <?= renderTreeHTML($treeNodes) ?>

            </div>

            <div style="width:360px; flex:0 0 360px; text-align:center;">
              <div class="orig-chart-wrap">
                <canvas id="pie" aria-label="Gráfico de pizza"></canvas>
              </div>
              <div style="margin-top:12px; color:#dfeff1; font-size:13px;">Gráfico gerado a partir das porcentagens normalizadas.</div>
            </div>
          </div>

          <script>
            const breakdown = <?= json_encode($percentages, JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;
            const labels = Object.keys(breakdown);
            const values = labels.map(l => parseFloat(breakdown[l].toFixed(2)));
            const ctx = document.getElementById('pie').getContext('2d');

            new Chart(ctx, {
              type: 'pie',
              data: { labels: labels, datasets: [{ data: values }] },
              options: {
                maintainAspectRatio: false,
                layout: { padding: { bottom: 24 } },
                plugins: {
                  legend: {
                    position: 'bottom',
                    labels: {
                      boxWidth: 12,
                      padding: 10,
                      usePointStyle: true
                    }
                  },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        const val = context.raw;
                        return context.label + ': ' + val.toFixed(2) + '%';
                      }
                    }
                  }
                }
              }
            });
          </script>

        <?php endif; ?>
      <?php endif; ?>

    </div>
  </main>

  <script src="/public/app.js"></script>
</body>
</html>

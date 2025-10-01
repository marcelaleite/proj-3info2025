<?php 


session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header("Location: /DW3/proj-3info2025/src/cadastro_menu_login/Control/index.php");
    exit;
}


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

function listarPerfisComUsuarios($pdo) {
    $sql = "
    SELECT 
        p.*,
        u.nome AS usuario_nome,
        pai.nome AS pai_nome,
        mae.nome AS mae_nome
    FROM perfil p
    JOIN usuario u ON u.id_usuario = p.usuario_idusuario
    LEFT JOIN usuario pai ON pai.id_usuario = p.id_pai
    LEFT JOIN usuario mae ON mae.id_usuario = p.id_mae
    ORDER BY u.nome ASC";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPerfilByUsuario($pdo, $idUsuario) {
    $sql = "SELECT * FROM perfil WHERE usuario_idusuario = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function calcularGeneticaCovinhas($perfilPai, $perfilMae) {
    $resultado = [
        'queixo' => ['genotipos' => [], 'fenotipos' => []],
        'bochecha' => ['genotipos' => [], 'fenotipos' => []]
    ];
    
  
    $paiQueixo = isset($perfilPai['cov_queixo']) ? (int)$perfilPai['cov_queixo'] : null;
    $maeQueixo = isset($perfilMae['cov_queixo']) ? (int)$perfilMae['cov_queixo'] : null;
    
 
    $debug = "Pai Queixo: " . ($paiQueixo === 1 ? 'COM' : ($paiQueixo === 0 ? 'SEM' : 'NULL')) . " | ";
    $debug .= "Mae Queixo: " . ($maeQueixo === 1 ? 'COM' : ($maeQueixo === 0 ? 'SEM' : 'NULL')) . " | ";
    
    // Determinar genótipos prováveis dos pais baseado nos fenótipos
    // Covinha é dominante (C), sem covinha é recessivo (c)
    $genotiposPaiQueixo = determinarGenotiposCovinha($paiQueixo);
    $genotiposMaeQueixo = determinarGenotiposCovinha($maeQueixo);
    
    // Calcular probabilidades dos filhos
    $probGenotiposQueixo = calcularCruzamentoGenetico($genotiposPaiQueixo, $genotiposMaeQueixo);
    $probFenotiposQueixo = [
        'com' => ($probGenotiposQueixo['CC'] ?? 0) + ($probGenotiposQueixo['Cc'] ?? 0),
        'sem' => ($probGenotiposQueixo['cc'] ?? 0)
    ];
    
    // CÁLCULO PARA COVINHA NA BOCHEcha
    $paiBochecha = isset($perfilPai['cov_bochecha']) ? (int)$perfilPai['cov_bochecha'] : null;
    $maeBochecha = isset($perfilMae['cov_bochecha']) ? (int)$perfilMae['cov_bochecha'] : null;
    
    $debug .= "Pai Bochecha: " . ($paiBochecha === 1 ? 'COM' : ($paiBochecha === 0 ? 'SEM' : 'NULL')) . " | ";
    $debug .= "Mae Bochecha: " . ($maeBochecha === 1 ? 'COM' : ($maeBochecha === 0 ? 'SEM' : 'NULL'));
    
    $genotiposPaiBochecha = determinarGenotiposCovinha($paiBochecha);
    $genotiposMaeBochecha = determinarGenotiposCovinha($maeBochecha);
    
    $probGenotiposBochecha = calcularCruzamentoGenetico($genotiposPaiBochecha, $genotiposMaeBochecha);
    $probFenotiposBochecha = [
        'com' => ($probGenotiposBochecha['CC'] ?? 0) + ($probGenotiposBochecha['Cc'] ?? 0),
        'sem' => ($probGenotiposBochecha['cc'] ?? 0)
    ];
    
    // Converter para porcentagem e arredondar
    $resultado['queixo']['genotipos'] = [
        'CC' => round(($probGenotiposQueixo['CC'] ?? 0) * 100, 1),
        'Cc' => round(($probGenotiposQueixo['Cc'] ?? 0) * 100, 1),
        'cc' => round(($probGenotiposQueixo['cc'] ?? 0) * 100, 1)
    ];
    
    $resultado['queixo']['fenotipos'] = [
        'com' => round(($probFenotiposQueixo['com'] ?? 0) * 100, 1),
        'sem' => round(($probFenotiposQueixo['sem'] ?? 0) * 100, 1)
    ];
    
    $resultado['bochecha']['genotipos'] = [
        'CC' => round(($probGenotiposBochecha['CC'] ?? 0) * 100, 1),
        'Cc' => round(($probGenotiposBochecha['Cc'] ?? 0) * 100, 1),
        'cc' => round(($probGenotiposBochecha['cc'] ?? 0) * 100, 1)
    ];
    
    $resultado['bochecha']['fenotipos'] = [
        'com' => round(($probFenotiposBochecha['com'] ?? 0) * 100, 1),
        'sem' => round(($probFenotiposBochecha['sem'] ?? 0) * 100, 1)
    ];
    
    $resultado['debug'] = $debug;
    return $resultado;
}

function determinarGenotiposCovinha($fenotipo) {
    // Covinha é dominante (C), sem covinha é recessivo (c)
    if ($fenotipo === 1) { // Com covinha - pode ser CC ou Cc
        return ['CC' => 0.5, 'Cc' => 0.5, 'cc' => 0.0];
    } elseif ($fenotipo === 0) { // Sem covinha - só pode ser cc
        return ['CC' => 0.0, 'Cc' => 0.0, 'cc' => 1.0];
    } else { // Desconhecido/NULL - distribuição igual
        return ['CC' => 0.333, 'Cc' => 0.333, 'cc' => 0.334];
    }
}

function calcularCruzamentoGenetico($genotiposPai, $genotiposMae) {
    $resultado = ['CC' => 0, 'Cc' => 0, 'cc' => 0];
    
    // Para cada combinação possível de genótipos dos pais
    foreach ($genotiposPai as $genPai => $probPai) {
        foreach ($genotiposMae as $genMae => $probMae) {
            if ($probPai > 0 && $probMae > 0) {
                $probCombinacao = $probPai * $probMae;
                $probsFilhos = cruzarGenotipos($genPai, $genMae);
                
                foreach ($probsFilhos as $genFilho => $probFilho) {
                    $resultado[$genFilho] += $probCombinacao * $probFilho;
                }
            }
        }
    }
    
    return $resultado;
}

function cruzarGenotipos($genPai, $genMae) {
  
    $cruzamentos = [
        'CCxCC' => ['CC' => 1.0, 'Cc' => 0.0, 'cc' => 0.0],
        'CCxCc' => ['CC' => 0.5, 'Cc' => 0.5, 'cc' => 0.0],
        'CCxcc' => ['CC' => 0.0, 'Cc' => 1.0, 'cc' => 0.0],
        'CcxCC' => ['CC' => 0.5, 'Cc' => 0.5, 'cc' => 0.0],
        'CcxCc' => ['CC' => 0.25, 'Cc' => 0.5, 'cc' => 0.25],
        'Ccxcc' => ['CC' => 0.0, 'Cc' => 0.5, 'cc' => 0.5],
        'ccxCC' => ['CC' => 0.0, 'Cc' => 1.0, 'cc' => 0.0],
        'ccxCc' => ['CC' => 0.0, 'Cc' => 0.5, 'cc' => 0.5],
        'ccxcc' => ['CC' => 0.0, 'Cc' => 0.0, 'cc' => 1.0]
    ];
    
    $chave = $genPai . 'x' . $genMae;
    return $cruzamentos[$chave] ?? ['CC' => 0.333, 'Cc' => 0.333, 'cc' => 0.334];
}

// LÓGICA PRINCIPAL
$covinhasCompletas = listarPerfisComUsuarios($pdo);
$usuarioLogadoId = $_SESSION['id_usuario'];
$perfilLogado = null;
$resultado = null;
$debugInfo = '';


foreach ($covinhasCompletas as $perfil) {
    if ($perfil['usuario_idusuario'] == $usuarioLogadoId) {
        $perfilLogado = $perfil;
        break;
    }
}


if ($perfilLogado && !empty($perfilLogado['id_pai']) && !empty($perfilLogado['id_mae'])) {
    $paiId = $perfilLogado['id_pai'];
    $maeId = $perfilLogado['id_mae'];
    
 
    $perfilPai = getPerfilByUsuario($pdo, $paiId);
    $perfilMae = getPerfilByUsuario($pdo, $maeId);
    
    if ($perfilPai && $perfilMae) {
        $resultado = calcularGeneticaCovinhas($perfilPai, $perfilMae);
        $debugInfo = $resultado['debug'] ?? '';
        
    
        $dadosPais = [
            'pai_nome' => $perfilPai['usuario_nome'] ?? 'Desconhecido',
            'mae_nome' => $perfilMae['usuario_nome'] ?? 'Desconhecido',
            'pai_queixo' => isset($perfilPai['cov_queixo']) ? ($perfilPai['cov_queixo'] ? 'com' : 'sem') : 'não informado',
            'pai_bochecha' => isset($perfilPai['cov_bochecha']) ? ($perfilPai['cov_bochecha'] ? 'com' : 'sem') : 'não informado',
            'mae_queixo' => isset($perfilMae['cov_queixo']) ? ($perfilMae['cov_queixo'] ? 'com' : 'sem') : 'não informado',
            'mae_bochecha' => isset($perfilMae['cov_bochecha']) ? ($perfilMae['cov_bochecha'] ? 'com' : 'sem') : 'não informado'
        ];
    } else {
        $resultado = ['erro' => 'Perfis dos pais não encontrados no banco de dados'];
    }
} elseif ($perfilLogado) {
    $resultado = ['erro' => 'Seu perfil não tem ambos os pais cadastrados'];
}
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Covinhas — Hereditariedade</title>
<link rel="stylesheet" href="/DW3/proj-3info2025/public/css/style.css">
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
  .debug-info { background: #e9ecef; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 0.9em; }
</style>
</head>
<body id="page-menu">
<nav class="topbar">
  <div class="nav-inner" style="display: flex; align-items: center;">
    <a class="nav-link" href="/DW3/proj-3info2025/src/cadastro_menu_login/Control/index.php">Início</a>
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
        <p><strong>Pais cadastrados:</strong> Sim (<?= htmlspecialchars($perfilLogado['pai_nome'] ?? 'Pai') ?> e <?= htmlspecialchars($perfilLogado['mae_nome'] ?? 'Mãe') ?>)</p>
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
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" style="text-align:center;">Nenhum perfil encontrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<?php if ($resultado && !isset($resultado['erro'])): ?>
  <div class="menu-card success-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Resultado das Probabilidades Genéticas</h2>
      <p>Baseado nos pais cadastrados no seu perfil:</p>
      
      <div class="debug-info">
        <strong>Características dos pais:</strong><br>
        <strong>Pai (<?= htmlspecialchars($dadosPais['pai_nome']) ?>):</strong> 
        Queixo <?= $dadosPais['pai_queixo'] ?> covinha | 
        Bochecha <?= $dadosPais['pai_bochecha'] ?> covinha<br>
        <strong>Mãe (<?= htmlspecialchars($dadosPais['mae_nome']) ?>):</strong> 
        Queixo <?= $dadosPais['mae_queixo'] ?> covinha | 
        Bochecha <?= $dadosPais['mae_bochecha'] ?> covinha
      </div>
    </div>

    <h3>Covinha no Queixo</h3>
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
          <td><?= $resultado['queixo']['genotipos']['CC'] ?>%</td>
          <td><?= $resultado['queixo']['genotipos']['Cc'] ?>%</td>
          <td><?= $resultado['queixo']['genotipos']['cc'] ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com covinha: <?= $resultado['queixo']['fenotipos']['com'] ?>% |
            Sem covinha: <?= $resultado['queixo']['fenotipos']['sem'] ?>%
          </td>
        </tr>
      </tbody>
    </table>

    <h3 style="margin-top:20px;">Covinha na Bochecha</h3>
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
          <td><?= $resultado['bochecha']['genotipos']['CC'] ?>%</td>
          <td><?= $resultado['bochecha']['genotipos']['Cc'] ?>%</td>
          <td><?= $resultado['bochecha']['genotipos']['cc'] ?>%</td>
        </tr>
        <tr>
          <td>Fenótipos</td>
          <td colspan="3">
            Com covinha: <?= $resultado['bochecha']['fenotipos']['com'] ?>% |
            Sem covinha: <?= $resultado['bochecha']['fenotipos']['sem'] ?>%
          </td>
        </tr>
      </tbody>
    </table>
    
    <?php if (!empty($debugInfo)): ?>
    <div class="debug-info" style="margin-top: 15px; font-size: 0.8em;">
      <strong>Debug:</strong> <?= htmlspecialchars($debugInfo) ?>
    </div>
    <?php endif; ?>
  </div>
<?php elseif ($resultado && isset($resultado['erro'])): ?>
  <div class="menu-card warning-card" style="margin-top:20px;">
    <div class="menu-header">
      <h2>Erro no Cálculo</h2>
      <p><?= htmlspecialchars($resultado['erro']) ?></p>
    </div>
  </div>
<?php endif; ?>

</div>
</section>
</body>
</html>
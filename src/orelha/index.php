<?php
// ----------------- Conexão -----------------
$mysqli = new mysqli("localhost", "root", "", "BioLineage");
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Carrega lista de usuários (uma vez só)
$usuarios = $mysqli->query("SELECT id_usuario, nome FROM usuario ORDER BY nome");

// ----------------- Função de probabilidade -----------------
function calcularProbabilidade($fenotipoPai, $fenotipoMae) {
    // "Com divisão" = solta (dominante) | "Sem divisão" = presa (recessivo)
    $isPaiSem = (trim($fenotipoPai) === 'Sem divisão');
    $isMaeSem = (trim($fenotipoMae) === 'Sem divisão');

    if ($isPaiSem && $isMaeSem) {
        // ee x ee => 100% recessivo (Sem divisão)
        $sem_assum = 100; $sem_min = 100; $sem_max = 100;
    } elseif (($isPaiSem && !$isMaeSem) || (!$isPaiSem && $isMaeSem)) {
        // ee x E_ => assumimos E_ como Ee -> 50% sem
        // faixa: 0% (se E_) = EE até 50% (se Ee)
        $sem_assum = 50; $sem_min = 0; $sem_max = 50;
    } else {
        // E_ x E_ => assumimos Ee x Ee -> 25% sem
        // faixa: 0% (se algum EE) a 25% (Ee x Ee)
        $sem_assum = 25; $sem_min = 0; $sem_max = 25;
    }

    $com_assum = 100 - $sem_assum;
    $com_min   = 100 - $sem_max;
    $com_max   = 100 - $sem_min;

    return [
        'assumido_sem' => $sem_assum,
        'faixa_sem'    => [$sem_min, $sem_max],
        'assumido_com' => $com_assum,
        'faixa_com'    => [$com_min, $com_max],
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Probabilidade Genética – Tipo de Orelha</title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="topbar" role="navigation" aria-label="Navegação principal">
    <nav class="nav-inner">
      <a class="nav-link" href="../cadastro_menu_login/View/menu.html">Menu</a>
      <a class="nav-link" href="#">Objetivo</a>
      <a class="nav-link" href="../cadastro_menu_login/View/sobre.html">Sobre</a>
    </nav>
  </header>

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="../../public/img/fundo_da_tela.mp4" type="video/mp4">
      Seu navegador não suporta vídeo.
    </video>
  </div>

  <div class="overlay" aria-hidden="true"></div>

  <main class="calc-screen" role="main" aria-label="Probabilidade genética">
    <div class="calc-wrapper">
      <section class="form-card">
        <h1>Probabilidade Genética — Orelha Presa x Solta</h1>
        <p class="subtitle">Selecione os responsáveis para estimar a chance de orelha <strong>presa</strong> (Sem divisão, recessivo) ou <strong>solta</strong> (Com divisão, dominante).</p>

        <form method="post">
          <div class="form-row">
            <label for="pai">Selecione o Pai</label>
            <select id="pai" name="pai" required>
              <option value="">— escolha —</option>
              <?php if ($usuarios && $usuarios->num_rows): ?>
                <?php while ($u = $usuarios->fetch_assoc()): ?>
                  <option value="<?= (int)$u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                <?php endwhile; ?>
              <?php else: ?>
                <option disabled>Nenhum usuário encontrado</option>
              <?php endif; ?>
            </select>
          </div>

          <?php
            if ($usuarios && $usuarios->num_rows) { $usuarios->data_seek(0); }
          ?>

          <div class="form-row">
            <label for="mae">Selecione a Mãe</label>
            <select id="mae" name="mae" required>
              <option value="">— escolha —</option>
              <?php if ($usuarios && $usuarios->num_rows): ?>
                <?php while ($u = $usuarios->fetch_assoc()): ?>
                  <option value="<?= (int)$u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                <?php endwhile; ?>
              <?php else: ?>
                <option disabled>Nenhum usuário encontrado</option>
              <?php endif; ?>
            </select>
          </div>

          <button type="submit" class="btn">Calcular probabilidade</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pai']) && !empty($_POST['mae'])) {
            $paiId = (int)$_POST['pai'];
            $maeId = (int)$_POST['mae'];

            $qPai = $mysqli->query("SELECT tipo_orelha FROM perfil WHERE usuario_idusuario = {$paiId} LIMIT 1");
            $qMae = $mysqli->query("SELECT tipo_orelha FROM perfil WHERE usuario_idusuario = {$maeId} LIMIT 1");

            if ($qPai && $qMae && $qPai->num_rows && $qMae->num_rows) {
                $pai = $qPai->fetch_assoc();
                $mae = $qMae->fetch_assoc();

                $res = calcularProbabilidade($pai['tipo_orelha'], $mae['tipo_orelha']);
                
                $npRow = $mysqli->query("SELECT nome FROM usuario WHERE id_usuario = {$paiId} LIMIT 1")->fetch_assoc();
                $nmRow = $mysqli->query("SELECT nome FROM usuario WHERE id_usuario = {$maeId} LIMIT 1")->fetch_assoc();
                $np = $npRow['nome'] ?? 'Pai';
                $nm = $nmRow['nome'] ?? 'Mãe';
                ?>
                <div class="result">
                  <p><strong>Pai:</strong> <?= htmlspecialchars($np) ?> — <em><?= htmlspecialchars($pai['tipo_orelha']) ?></em></p>
                  <p><strong>Mãe:</strong> <?= htmlspecialchars($nm) ?> — <em><?= htmlspecialchars($mae['tipo_orelha']) ?></em></p>

                  <table>
                    <tr>
                      <th></th>
                      <th>Assumido*</th>
                      <th>Faixa possível</th>
                    </tr>
                    <tr>
                      <td>Filho com <strong>orelha presa</strong> (Sem divisão)</td>
                      <td><?= (int)$res['assumido_sem'] ?>%</td>
                      <td><?= (int)$res['faixa_sem'][0] ?>% a <?= (int)$res['faixa_sem'][1] ?>%</td>
                    </tr>
                    <tr>
                      <td>Filho com <strong>orelha solta</strong> (Com divisão)</td>
                      <td><?= (int)$res['assumido_com'] ?>%</td>
                      <td><?= (int)$res['faixa_com'][0] ?>% a <?= (int)$res['faixa_com'][1] ?>%</td>
                    </tr>
                  </table>

                  <p class="muted">
                    * Assumimos que fenótipos dominantes desconhecidos (Com divisão) são heterozigotos (Ee).  
                    A faixa mostra os limites caso um ou ambos dominantes sejam homozigotos (EE).
                  </p>
                </div>
                <?php
            } else {
                echo '<div class="result"><p>Não encontrei <strong>perfil</strong> para Pai e/ou Mãe selecionados.</p></div>';
            }
        }
        ?>
      </section>
    </div>
  </main>

</body>
</html>

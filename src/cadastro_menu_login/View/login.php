<?php
session_start();
include("../Control/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id_usuario, nome, senha FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_usuario, $nome, $senhaHash);

    if ($stmt->fetch()) {
        if (password_verify($senha, $senhaHash)) {
            $_SESSION['usuario_id'] = $id_usuario;
            $_SESSION['usuario_nome'] = $nome;
            header("Location: menu.php");
            exit;
        } else {
            $error = "Senha incorreta!";
        }
    } else {
        $error = "Usuário não encontrado!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Biolineage — Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/styles.css">
</head>
<body id="page-login">

  <!-- vídeo de fundo -->
  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
      Seu navegador não suporta reprodução de vídeo.
    </video>
  </div>

  <!-- Tela de login com layout igual ao cadastro -->
  <main class="register-screen" role="main" aria-label="Login">
    <div class="register-wrapper" role="region" aria-labelledby="formTitle">
      <div class="form-card">
        <div class="form-header">
          <h2 id="formTitle">Bem-vindo de volta!</h2>
          <p>Faça login para acessar a plataforma e seus projetos.</p>
        </div>

        <!-- Formulário de login -->
        <form method="POST" action="login.php">
          <div class="row">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="exemplo@dominio.com" required />
          </div>

          <div class="row">
            <label>Senha</label>
            <input type="password" name="senha" placeholder="Sua senha" required />
          </div>

          <div class="form-actions">
            <a class="btn ghost" href="homescreen.html">Voltar</a>
            <button type="submit" class="btn primary">Entrar</button>
          </div>
        </form>

        <?php if (isset($error)): ?>
          <p style="color:red; text-align:center; margin-top: 10px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <p class="not-account" style="text-align:center; margin-top: 20px;">
          Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
        </p>
      </div>
    </div>
  </main>

</body>
</html>

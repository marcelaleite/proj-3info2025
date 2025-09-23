<?php include("../Control/db.php"); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Marcela — Cadastro</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/styles.css">
</head>
<body id="page-cadastro">

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
      Seu navegador não suporta reprodução de vídeo.
    </video>
  </div>

  <main class="register-screen" role="main" aria-label="Cadastro">
    <div class="register-wrapper" role="region" aria-labelledby="formTitle">
      <div class="form-card">
        <div class="form-header">
          <h2 id="formTitle">Crie sua conta</h2>
          <p>Cadastre-se para acessar a plataforma e iniciar projetos.</p>
        </div>

        <!-- Formulário -->
        <form method="POST" action="cadastro.php">
          <div class="row">
            <label>Nome completo</label>
            <input type="text" name="nome" placeholder="Seu nome completo" required />
          </div>

          <div class="row two-cols">
            <div>
              <label>E-mail</label>
              <input type="email" name="email" placeholder="exemplo@dominio.com" required />
            </div>
            <div>
              <label>Telefone</label>
              <input type="tel" name="telefone" placeholder="(xx) xxxxx-xxxx" />
            </div>
          </div>

          <div class="row two-cols">
            <div>
              <label>Senha</label>
              <input type="password" name="senha" placeholder="Mínimo 6 caracteres" required />
            </div>
            <div>
              <label>Confirmar senha</label>
              <input type="password" name="confirm" placeholder="Repita a senha" required />
            </div>
          </div>

          <div class="row two-cols">
            <div>
              <label>Data de nascimento</label>
              <input type="date" name="dt_nascimento" />
            </div>
            <div>
              <label>Instituição</label>
              <input type="text" name="instituicao" placeholder="Universidade / Empresa" />
            </div>
          </div>

          <div class="row">
            <label>Breve descrição / área de pesquisa</label>
            <textarea name="descricao" rows="3" placeholder="Ex.: genética molecular, bioinformática..."></textarea>
          </div>

          <div class="form-actions">
            <a class="btn ghost" href="homescreen.html">Voltar</a>
            <button type="submit" class="btn primary">Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $telefone = $_POST['telefone'];
      $senha = $_POST['senha'];
      $confirm = $_POST['confirm'];
      $dt_nascimento = $_POST['dt_nascimento'];
      $instituicao = $_POST['instituicao'];
      $descricao = $_POST['descricao'];

      if ($senha !== $confirm) {
          echo "<p style='color:red;text-align:center;'>As senhas não coincidem!</p>";
      } else {
          // Cria hash da senha antes de salvar
          $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

          $sql = "INSERT INTO usuario (nome, email, telefone, senha, dataNascimento, instituicao, descricao) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sssssss", $nome, $email, $telefone, $senhaHash, $dt_nascimento, $instituicao, $descricao);

          if ($stmt->execute()) {
              echo "<p style='color:green;text-align:center;'>Cadastro realizado com sucesso!</p>";
              // redireciona para menu
              echo "<script>setTimeout(() => { window.location.href='menu.html'; }, 1500);</script>";
          } else {
              echo "<p style='color:red;text-align:center;'>Erro: " . $stmt->error . "</p>";
          }

          $stmt->close();
      }
  }
  ?>
</body>
</html>

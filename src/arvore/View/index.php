<!DOCTYPE html>
<html lang="pt-BR">
<?php include "head.html"; ?>
<body id="page-cadastro" style="min-height:100vh; position:relative; margin:0;">
    <?php 
        include "topnavigationfundo.html";
    ?>

  <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:60vh; color:white;">
    <h1>Bem-vindo ao Sistema de Cadastro</h1>
    <p>Este sistema permite cadastrar usuários e construir sua árvore genealógica de forma simples e intuitiva.</p>
    <div style="margin-top:30px; display:flex; gap:20px;">
      <a href="cadastrousuario.php" style="padding:15px 30px; background:#007bff; color:#fff; border:none; border-radius:5px; text-decoration:none; font-size:1.1em; transition:background 0.2s;">
        Cadastro de Usuários
      </a>
      <a href="cadastroarvore.php" style="padding:15px 30px; background:#28a745; color:#fff; border:none; border-radius:5px; text-decoration:none; font-size:1.1em; transition:background 0.2s;">
        Construir Árvore Genealógica
      </a>
    </div>
  </div>






    <div style="
    position:fixed; 
    left:0; 
    bottom:0; 
    width:100%; 
    z-index:100; 
    display:flex; 
    justify-content:center; 
    align-items:center;">
        <?php include "rodapé.html"; ?>
    </div>

  <script>
    document.getElementById('genealogicForm').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Cadastro realizado com sucesso!');
      window.location.href = '../../../index.php';
    });
  </script>

</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<?php include "head.html"; ?>
<body id="page-cadastro" style="min-height:100vh; position:relative; margin:0;">
    <?php 
        include "topnavigationfundo.html";
    ?>

    <div style="padding-bottom: 80px;">
        <?php 
            include "form.html";
        ?>
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
      // Aqui você poderia redirecionar para a próxima página
      // window.location.href = 'menu.html';
    });
  </script>

</body>
</html>
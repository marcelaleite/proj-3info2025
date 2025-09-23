<?php
include("../Control/db.php");

// consulta adaptada ao novo banco
$sql = "SELECT 
            u.id_usuario,
            u.nome,
            u.email,
            u.telefone,
            u.dataNascimento,
            u.instituicao,
            u.descricao,
            pe.id_perfil,
            mae.nome AS mae,
            pai.nome AS pai,
            d.nome AS doenca
        FROM usuario u
        LEFT JOIN perfil pe ON pe.usuario_idusuario = u.id_usuario
        LEFT JOIN usuario mae ON mae.id_usuario = pe.id_mae
        LEFT JOIN usuario pai ON pai.id_usuario = pe.id_pai
        LEFT JOIN doenca d ON d.id_doenca = pe.doenca_genealogica
        ORDER BY u.id_usuario ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background: #34495e;
            color: #fff;
            font-size: 14px;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background: #f9fbfd;
        }

        table tr:hover {
            background: #eef5ff;
        }

        table td {
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            color: #333;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #999;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #3498db;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #2980b9;
        }

        .background-video { position: fixed; inset:0; z-index:-2; overflow:hidden; }
        .background-video video { width:100%; height:100%; object-fit:cover; display:block; }
    </style>
</head>
<body>
    <div class="background-video" aria-hidden="true">
        <video autoplay loop muted playsinline>
        <source src="../../../public/img/fundo_da_tela.mp4" type="video/mp4" />
        Seu navegador não suporta reprodução de vídeo.
        </video>
    </div>
    <div class="container">
        <h1>Usuários Cadastrados</h1>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Data Nascimento</th>
                    <th>Instituição</th>
                    <th>Descrição</th>
                    <th>Mãe</th>
                    <th>Pai</th>
                    <th>Doença</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id_usuario"] ?></td>
                    <td><?= $row["nome"] ?></td>
                    <td><?= $row["email"] ?></td>
                    <td><?= $row["telefone"] ?></td>
                    <td><?= $row["dataNascimento"] ?></td>
                    <td><?= $row["instituicao"] ?></td>
                    <td><?= $row["descricao"] ?></td>
                    <td><?= $row["mae"] ?? '-' ?></td>
                    <td><?= $row["pai"] ?? '-' ?></td>
                    <td><?= $row["doenca"] ?? '-' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="no-data">Nenhum usuário encontrado.</p>
        <?php endif; ?>
        <a href="index.php" class="back-btn">⬅ Voltar</a>
    </div>
</body>
</html>

<?php
// save_perfil.php

require_once '../model/Classe.class.php'; // ou o caminho correto para o seu arquivo Perfil.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados recebidos via POST
    $usuarioId = $_POST['usuarioId'] ?? null;
    $sexo = $_POST['sexo'] ?? null;
    $corOlho = $_POST['corOlho'] ?? null;
    $corCabelo = $_POST['corCabelo'] ?? null;
    $tipoOrelha = $_POST['tipoOrelha'] ?? null;
    $tipoSanguineo = $_POST['tipoSanguineo'] ?? null;
    $daltonismo = isset($_POST['daltonismo']) ? filter_var($_POST['daltonismo'], FILTER_VALIDATE_BOOLEAN) : false;
    $sardas = isset($_POST['sardas']) ? filter_var($_POST['sardas'], FILTER_VALIDATE_BOOLEAN) : false;
    $fator = $_POST['fator'] ?? null;
    $covQueixo = isset($_POST['covQueixo']) ? filter_var($_POST['covQueixo'], FILTER_VALIDATE_BOOLEAN) : false;
    $covBochecha = isset($_POST['covBochecha']) ? filter_var($_POST['covBochecha'], FILTER_VALIDATE_BOOLEAN) : false;
    $albinismo = isset($_POST['albinismo']) ? filter_var($_POST['albinismo'], FILTER_VALIDATE_BOOLEAN) : false;
    $nacionalidade = $_POST['nacionalidade'] ?? null;
    $doencaGenealogica = $_POST['doencaGenealogica'] ?? null;
    $idPai = $_POST['idPai'] ?? null;
    $idMae = $_POST['idMae'] ?? null;

    if ($usuarioId && $sexo) {
        $perfil = new Perfil($usuarioId, $sexo, $corOlho, $corCabelo, $tipoOrelha, $tipoSanguineo,
                             $daltonismo, $sardas, $fator, $covQueixo, $covBochecha,
                             $albinismo, $nacionalidade, $doencaGenealogica, $idPai, $idMae);

        if ($perfil->inserir()) {
            echo json_encode(['success' => true, 'id' => $usuarioId]); // Retorna o ID do novo registro
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha ao inserir no banco de dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Dados incompletos para inserção.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Para atualizar, você precisa obter os dados do corpo da requisição
    parse_str(file_get_contents("php://input"), $putData);

    $usuarioId = $putData['usuarioId'] ?? null;
    $sexo = $putData['sexo'] ?? null;
    $corOlho = $putData['corOlho'] ?? null;
    $corCabelo = $putData['corCabelo'] ?? null;
    $tipoOrelha = $putData['tipoOrelha'] ?? null;
    $tipoSanguineo = $putData['tipoSanguineo'] ?? null;
    $daltonismo = isset($putData['daltonismo']) ? filter_var($putData['daltonismo'], FILTER_VALIDATE_BOOLEAN) : false;
    $sardas = isset($putData['sardas']) ? filter_var($putData['sardas'], FILTER_VALIDATE_BOOLEAN) : false;
    $fator = $putData['fator'] ?? null;
    $covQueixo = isset($putData['covQueixo']) ? filter_var($putData['covQueixo'], FILTER_VALIDATE_BOOLEAN) : false;
    $covBochecha = isset($putData['covBochecha']) ? filter_var($putData['covBochecha'], FILTER_VALIDATE_BOOLEAN) : false;
    $albinismo = isset($putData['albinismo']) ? filter_var($putData['albinismo'], FILTER_VALIDATE_BOOLEAN) : false;
    $nacionalidade = $putData['nacionalidade'] ?? null;
    $doencaGenealogica = $putData['doencaGenealogica'] ?? null;
    $idPai = $putData['idPai'] ?? null;
    $idMae = $putData['idMae'] ?? null;

    if ($usuarioId && $sexo) {
        $perfil = new Perfil($usuarioId, $sexo, $corOlho, $corCabelo, $tipoOrelha, $tipoSanguineo,
                             $daltonismo, $sardas, $fator, $covQueixo, $covBochecha,
                             $albinismo, $nacionalidade, $doencaGenealogica, $idPai, $idMae);

        if ($perfil->alterar()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha ao atualizar no banco de dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Dados incompletos para atualização.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
}
?>
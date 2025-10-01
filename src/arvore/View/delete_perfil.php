<?php
// delete_perfil.php

require_once '../model/Class.class.php'; // ou o caminho correto para o seu arquivo Perfil.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id'] ?? null;

    if ($id) {
        // Criar um objeto Perfil temporário apenas para excluir, usando o ID como usuarioId
        // A classe Perfil usa usuarioId como chave primária, então o ID do banco é o usuarioId
        $perfil = new Perfil($id, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

        if ($perfil->excluir()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha ao excluir do banco de dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID não fornecido para exclusão.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
}
?>
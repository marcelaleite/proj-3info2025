<?php
header("Content-Type: application/json; charset=utf-8");

// arquivo onde vai salvar
$arquivo = __DIR__ . "/../Model/tabela.json";

// lê os dados recebidos
$dados = json_decode(file_get_contents("php://input"), true);

// verifica se veio algo
if (!$dados || !isset($dados["tabela"])) {
    echo json_encode(["mensagem" => "Nenhum dado recebido"]);
    exit;
}

// se o arquivo já existe, carrega os dados antigos
if (file_exists($arquivo)) {
    $conteudo = json_decode(file_get_contents($arquivo), true);
    if (!is_array($conteudo)) {
        $conteudo = [];
    }
} else {
    $conteudo = [];
}

// atualiza a tabela
$conteudo["tabela"] = $dados["tabela"];

// salva de volta no JSON
if (file_put_contents($arquivo, json_encode($conteudo, JSON_PRETTY_PRINT))) {
    echo json_encode(["mensagem" => "Tabela salva com sucesso!"]);
} else {
    echo json_encode(["mensagem" => "Erro ao salvar"]);
}

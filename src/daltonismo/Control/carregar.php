<?php
header("Content-Type: application/json; charset=utf-8");

$arquivo = __DIR__ . "/../Model/tabela.json";

if (file_exists($arquivo)) {
    // lê o conteúdo bruto do arquivo
    $conteudo = file_get_contents($arquivo);

    // tenta decodificar
    $json = json_decode($conteudo, true);

    if (is_array($json)) {
        echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["tabela" => []]); // arquivo existe mas tá corrompido
    }
} else {
    echo json_encode(["tabela" => []]); // arquivo ainda não existe
}

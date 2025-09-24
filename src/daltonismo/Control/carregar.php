<?php
echo json_encode(["debug" => realpath($arquivo)]);
exit;

header("Content-Type: application/json; charset=utf-8");

// caminho correto para a pasta Model
$arquivo = __DIR__ . "/../Model/tabela.json";

if (file_exists($arquivo)) {
    echo file_get_contents($arquivo);
} else {
    echo json_encode(["tabela" => []]);
}

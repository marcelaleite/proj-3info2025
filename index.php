<?php
// index.php

$page = isset($_GET['page']) ? $_GET['page'] : 'homescreen';
$baseCadastro = __DIR__ . '/src/cadastro_menu_login/View/';

// Rotas
$routes = [
    // fluxo inicial
    'homescreen' => $baseCadastro . 'homescreen.html',
    'cadastro'   => $baseCadastro . 'cadastro.html',
    'menu'       => $baseCadastro . 'menu.html',

    // páginas internas do grupo cadastro_menu_login
    'dashboard'  => $baseCadastro . 'dashboard.html',
    'projetos'   => $baseCadastro . 'projetos.html',
    'simulacoes' => $baseCadastro . 'simulacoes.html',
    'calc-olhos' => $baseCadastro . 'calc-olhos.html',
    'contato'    => $baseCadastro . 'contato.html',
    'objetivo'   => $baseCadastro . 'objetivo.html',
    'sobre'      => $baseCadastro . 'sobre.html',

    // páginas de outros grupos
    'arvore'     => __DIR__ . '/src/arvore/View/arvore.html',
    'doenca'     => __DIR__ . '/src/app/View/doenca.html',
    'tipo'       => __DIR__ . '/src/app/View/tipoSanquineo.html',
    'albinismo'  => __DIR__ . '/src/albinismo/View/albinismo.html',
    'covinhas'   => __DIR__ . '/src/covinhas/view/form_cad_covinhas.php',
    'daltonismo' => __DIR__ . '/src/cal_daltonismo/cad_daltonismo.html',
];

if (array_key_exists($page, $routes)) {
    $file = $routes[$page];

    if (!file_exists($file)) {
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        exit;
    }

    // se for PHP → inclui
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        include $file;
    } else {
        // se for HTML → lê e injeta <base>
        $html = file_get_contents($file);

        $html = preg_replace(
            '/<head>/i',
            '<head><base href="/proj-3info2025/src/cadastro_menu_login/View/">',
            $html,
            1
        );

        echo $html;
    }
} else {
    // rota inválida → homescreen
    $html = file_get_contents($baseCadastro . 'homescreen.html');
    $html = preg_replace(
        '/<head>/i',
        '<head><base href="/proj-3info2025/src/cadastro_menu_login/View/">',
        $html,
        1
    );
    echo $html;
}
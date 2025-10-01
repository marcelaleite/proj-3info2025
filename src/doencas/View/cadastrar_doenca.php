<?php
session_start();

if (!isset($_SESSION['doencas'])) {
    $_SESSION['doencas'] = [
        ['nome' => 'Albinismo'],
        ['nome' => 'Daltonismo'],
        ['nome' => 'Hemofilia']
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nomeDoenca'])) {
    $novaDoenca = [
        'nome' => htmlspecialchars($_POST['nomeDoenca'])
    ];
    
    $_SESSION['doencas'][] = $novaDoenca;
}

header('Location: doenca.php');
exit;

<?php
require_once '../Model/Sarda.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pai = strtolower($_POST['pai'] ?? '');
    $mae = strtolower($_POST['mae'] ?? '');

    $chance = 0;
    if ($pai === 'sim' && $mae === 'sim') {
        $chance = "75 ou 100";
    } elseif ($pai === 'sim' || $mae === 'sim') {
        $chance = "25 ou 100";
    } else {
        $chance = 0;
    }

    $sarda = new Sarda(
        null,  // id_perfil
        1,     // usuario_id_usuario
        null,  // id_pai
        null,  // id_mae
        "Chance: $chance%"
    );

    $_SESSION['chance_sarda'] = $chance;

    header("Location: ../view/resultado_sardas.php");
    exit;
}

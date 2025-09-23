<?php

require_once '../Model/TipoSanguineo.class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pai_tipo = $_POST['pai_tipo'];
    $pai_rh = $_POST['pai_rh'];
    $mae_tipo = $_POST['mae_tipo'];
    $mae_rh = $_POST['mae_rh'];

    $tipoSanguineo = new TipoSanguineo();
    $filho_tipos = $tipoSanguineo->calcularTipoSanguineo($pai_tipo, $mae_tipo);
    $filho_rh = $tipoSanguineo->calcularFatorRh($pai_rh, $mae_rh);

    echo "<h2>Resultados da Previsão</h2>";
    echo "<p>Tipo sanguíneo do pai: {$pai_tipo}{$pai_rh}</p>";
    echo "<p>Tipo sanguíneo da mãe: {$mae_tipo}{$mae_rh}</p>";
    echo "<p>Possíveis tipos sanguíneos do filho: " . implode(', ', $filho_tipos) . "</p>";
    echo "<p>Possíveis fatores Rh do filho: " . implode(', ', $filho_rh) . "</p>";

}

?>
<?php
<<<<<<< HEAD

require_once '../Model/TipoSanguineo.class.php';
=======
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Model/TipoSanguineo.class.php';
require_once '../../../config/config.inc.php';
require_once '../../DAO/Database.class.php';
>>>>>>> origin/PrevisãoTipoSanguíneo

$db = Database::getConexao();
$tipoSanguineo = new TipoSanguineo($db);

$resultado = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
<<<<<<< HEAD
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
=======
    $idUsuario = $_POST['id_usuario'];
    $resultado = $tipoSanguineo->calcular($idUsuario);
}

$usuarios = $tipoSanguineo->getUsuarios();

include '../View/tipoSanquineo.php';
?>
>>>>>>> origin/PrevisãoTipoSanguíneo

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Model/TipoSanguineo.class.php';
require_once '../../../config/config.inc.php';
require_once '../../DAO/Database.class.php';

$db = Database::getConexao();
$tipoSanguineo = new TipoSanguineo($db);

$resultado = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsuario = $_POST['id_usuario'];
    $resultado = $tipoSanguineo->calcular($idUsuario);
}

$usuarios = $tipoSanguineo->getUsuarios();

include '../View/tipoSanquineo.php';
?>
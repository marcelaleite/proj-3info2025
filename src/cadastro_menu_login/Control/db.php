<?php
$host = "localhost";
$user = "root"; // ajuste se seu MySQL tiver usuário/senha diferentes
$pass = "";
$dbname = "BioLineage";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

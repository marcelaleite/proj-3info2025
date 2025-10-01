<?php
$host = "localhost";
$user = "marcela"; // ajuste se seu MySQL tiver usuário/senha diferentes
$pass = "marcela01";
$dbname = "BioLineage";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

<?php
require_once __DIR__ . "/Database.php";

class Card {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Criar novo card
    public function criar($nome, $imagem, $link) {
        $stmt = $this->conn->prepare("INSERT INTO cards (nome, imagem, link) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $nome, $imagem, $link);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Erro ao preparar query: " . $this->conn->error);
        }
    }

    // Listar todos os cards
    public function listar() {
        $result = $this->conn->query("SELECT * FROM cards ORDER BY id DESC");
        $cards = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $cards[] = $row;
            }
        }
        return $cards;
    }
}
?>

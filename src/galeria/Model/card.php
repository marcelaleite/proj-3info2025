<?php
require_once "Database.php";

class Card {
    private $conn;
    private $table_name = "cards";

    public $id;
    public $nome;
    public $imagem;
    public $link;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function criar($nome, $imagem, $link) {
        // Corrige link
        if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
            $link = "https://" . $link;
        }

        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (nome, imagem, link) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $imagem, $link);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function listar() {
        $result = $this->conn->query("SELECT * FROM {$this->table_name} ORDER BY id ASC");
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

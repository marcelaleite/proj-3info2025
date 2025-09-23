<?php
include_once  __DIR__.'../../DAO/Database.php';class Doenca {
    private $id;
    private $nome;

    // Constructor
    public function __construct($nome) {
        $this->nome = $nome;
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }


    // Method to save to database
    public function save($conexao) {
        $stmt = $conexao->prepare("INSERT INTO doenca (nome) VALUES (?, ?)");
        $stmt->bind_param("ss", $this->nome);
        $stmt->execute();
        $this->id = $conexao->insert_id;
        $stmt->close();
    }
}

?>

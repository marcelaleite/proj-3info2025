<?php
include_once __DIR__ . '/../../DAO/Database.class.php';

class Doenca {
    private $id;
    private $nome;

    public function __construct($nome) {
        $this->nome = $nome;
    }

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

    // Salvar no banco de dados
    public function save($conexao) {
        $stmt = $conexao->prepare("INSERT INTO doenca (nome) VALUES (?)");
        $stmt->bind_param("s", $this->nome);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->id = $conexao->insert_id;
        }

        $stmt->close();
    }

    // Listar todas as doenças cadastradas
    public static function listar($conexao) {
        $result = $conexao->query("SELECT id, nome FROM doenca ORDER BY nome ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

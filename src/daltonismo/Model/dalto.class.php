<?php
require_once '../../cadastro_menu_login/Model/Classe.class.php';

class Resultado {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // CREATE
    public function create($pontuacao, $daltonismo) {
        $sql = "INSERT INTO resultados_daltonismo (pontuacao, daltonismo) VALUES (:pontuacao, :daltonismo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":pontuacao", $pontuacao, PDO::PARAM_INT);
        $stmt->bindValue(":daltonismo", $daltonismo);
        return $stmt->execute();
    }

    // READ
    public function read() {
        $sql = "SELECT * FROM resultados_daltonismo ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $daltonismo) {
        $sql = "UPDATE resultados_daltonismo SET daltonismo = :daltonismo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":daltonismo", $daltonismo);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM resultados_daltonismo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

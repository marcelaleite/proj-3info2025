<?php
require_once __DIR__ . "/Sarda.class.php";

class SardaDAO {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(Sarda $s): int {
        $sql = "INSERT INTO sardas (usuario_id, id_pai, id_mae, sarda) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$s->getUsuarioId(), $s->getIdPai(), $s->getIdMae(), $s->getSarda()]);
        return $this->pdo->lastInsertId();
    }

    public function listAll(): array {
        $sql = "SELECT * FROM sardas";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Sarda $s): bool {
        $sql = "UPDATE sardas SET usuario_id=?, id_pai=?, id_mae=?, sarda=? WHERE id_perfil=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $s->getUsuarioId(),
            $s->getIdPai(),
            $s->getIdMae(),
            $s->getSarda(),
            $s->getIdPerfil()
        ]);
    }

    public function delete(int $idPerfil): bool {
        $sql = "DELETE FROM sardas WHERE id_perfil=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idPerfil]);
    }
}

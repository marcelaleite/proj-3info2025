<?php
namespace App\Albinismo\Model;

use PDO;
use App\Albinismo\Model\Albinismo;

require_once __DIR__ . '/Albinismo.class.php';

class AlbinismoDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Albinismo $a): int
    {
        $sql = "INSERT INTO perfil (
                    usuario_idusuario, id_pai, id_mae, albinismo
                ) VALUES (:u, :p, :m, :alb)";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            ':u'   => $a->getUsuarioId(),
            ':p'   => $a->getIdPai(),
            ':m'   => $a->getIdMae(),
            ':alb' => $a->getPossuiAlbinismo(),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(Albinismo $a): bool
    {
        $sql = "UPDATE perfil 
                   SET usuario_idusuario=:u, id_pai=:p, id_mae=:m, albinismo=:alb 
                 WHERE id_perfil=:id";
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            ':u'   => $a->getUsuarioId(),
            ':p'   => $a->getIdPai(),
            ':m'   => $a->getIdMae(),
            ':alb' => $a->getPossuiAlbinismo(),
            ':id'  => $a->getIdAlbinismo(),
        ]);
    }

    public function delete(int $idAlbinismo): bool
    {
        $st = $this->pdo->prepare("DELETE FROM perfil WHERE id_perfil = :id");
        return $st->execute([':id' => $idAlbinismo]);
    }

    public function findById(int $idAlbinismo): ?array
    {
        $sql = "SELECT p.*, u.nome as usuario_nome
                  FROM perfil p
                  JOIN usuario u ON u.id_usuario = p.usuario_idusuario
                 WHERE p.id_perfil = :id";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $idAlbinismo]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function listAll(): array
    {
        $sql = "SELECT p.*, u.nome as usuario_nome,
                       pai.nome as nome_pai, mae.nome as nome_mae
                  FROM perfil p
                  JOIN usuario u ON u.id_usuario = p.usuario_idusuario
             LEFT JOIN usuario pai ON pai.id_usuario = p.id_pai
             LEFT JOIN usuario mae ON mae.id_usuario = p.id_mae
              ORDER BY u.nome";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listUsuariosComPerfil(): array
    {
        $sql = "SELECT u.id_usuario, u.nome
                  FROM usuario u
                 WHERE EXISTS (SELECT 1 FROM perfil p WHERE p.usuario_idusuario = u.id_usuario)
              ORDER BY u.nome";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerfilByUsuario(int $idUsuario): ?array
    {
        $st = $this->pdo->prepare("SELECT * FROM perfil WHERE usuario_idusuario = :id LIMIT 1");
        $st->execute([':id' => $idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getPais(int $idUsuario): array
    {
        $st = $this->pdo->prepare("SELECT id_pai, id_mae FROM perfil WHERE usuario_idusuario=:u LIMIT 1");
        $st->execute([':u' => $idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['id_pai' => null, 'id_mae' => null];
    }

    public function getAvosDe(int $idUsuario): array
    {
        $pais = $this->getPais($idUsuario);
        $out = [
            'paterno' => ['avo' => null, 'avoh' => null],
            'materno' => ['avo' => null, 'avoh' => null],
        ];
        foreach (['paterno' => $pais['id_pai'], 'materno' => $pais['id_mae']] as $lado => $idPaiMae) {
            if (!$idPaiMae) continue;
            $perfilPaiMae = $this->getPerfilByUsuario((int)$idPaiMae);
            if ($perfilPaiMae) {
                $avoIds = [$perfilPaiMae['id_pai'] ?? null, $perfilPaiMae['id_mae'] ?? null];
                $labels = ['avo', 'avoh'];
                foreach ($avoIds as $k => $avoId) {
                    if ($avoId) {
                        $out[$lado][$labels[$k]] = $this->getPerfilByUsuario((int)$avoId);
                    }
                }
            }
        }
        return $out;
    }

    public function getIrmaos(int $idUsuario): array
    {
        $pais = $this->getPais($idUsuario);
        if (empty($pais['id_pai']) || empty($pais['id_mae'])) return [];
        $st = $this->pdo->prepare("SELECT p.* 
                                     FROM perfil p
                                    WHERE p.id_pai = :p 
                                      AND p.id_mae = :m 
                                      AND p.usuario_idusuario <> :u");
        $st->execute([
            ':p' => $pais['id_pai'], 
            ':m' => $pais['id_mae'], 
            ':u' => $idUsuario
        ]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}

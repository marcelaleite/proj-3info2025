<?php
namespace App\Covinhas\DAO;

use PDO;

class CovinhaDAO
{
    public function __construct(private PDO $pdo) {}

    public function listarPerfisComUsuarios(): array
    {
        $sql = "
        SELECT 
            p.*,
            u.nome AS usuario_nome,
            pai.nome AS pai_nome,
            mae.nome AS mae_nome
        FROM perfil p
        JOIN usuario u   ON u.id_usuario = p.usuario_idusuario
        LEFT JOIN usuario pai ON pai.id_usuario = p.id_pai
        LEFT JOIN usuario mae ON mae.id_usuario = p.id_mae
        ORDER BY u.nome ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerfilByUsuario(int $idUsuario): ?array
    {
        $st = $this->pdo->prepare("SELECT * FROM perfil WHERE usuario_idusuario = :u LIMIT 1");
        $st->execute([':u' => $idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getPais(int $idUsuario): array
    {
        $st = $this->pdo->prepare("SELECT id_pai, id_mae FROM perfil WHERE usuario_idusuario = :u LIMIT 1");
        $st->execute([':u' => $idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['id_pai' => null, 'id_mae' => null];
    }

    public function getAvosDeUsuario(int $idUsuario): array
    {
        $out = [
            'paterno' => ['avo' => null, 'avoh' => null],
            'materno' => ['avo' => null, 'avoh' => null],
        ];
        $pais = $this->getPais($idUsuario);

        foreach (['paterno' => $pais['id_pai'] ?? null, 'materno' => $pais['id_mae'] ?? null] as $lado => $idPaiMae) {
            if (!$idPaiMae) continue;
            $perfilPaiMae = $this->getPerfilByUsuario((int)$idPaiMae);
            if (!$perfilPaiMae) continue;

            $idAvo  = $perfilPaiMae['id_pai'] ?? null;
            $idAvoh = $perfilPaiMae['id_mae'] ?? null;

            $out[$lado]['avo']  = $idAvo  ? $this->getPerfilByUsuario((int)$idAvo)  : null;
            $out[$lado]['avoh'] = $idAvoh ? $this->getPerfilByUsuario((int)$idAvoh) : null;
        }
        return $out;
    }

    public function getIrmaos(int $idUsuario): array
    {
        $pais = $this->getPais($idUsuario);
        if (empty($pais['id_pai']) || empty($pais['id_mae'])) return [];
        $st = $this->pdo->prepare("
            SELECT p.* FROM perfil p
            WHERE p.id_pai = :p AND p.id_mae = :m AND p.usuario_idusuario <> :u
        ");
        $st->execute([':p' => $pais['id_pai'], ':m' => $pais['id_mae'], ':u' => $idUsuario]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarUsuarios(): array
    {
        $sql = "SELECT id_usuario, nome FROM usuario ORDER BY nome ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
};
<?php
namespace App\Covinhas\Controller;

use App\Covinhas\DAO\CovinhaDAO;
use PDO;

class CovinhaController
{
    private CovinhaDAO $dao;

    public function __construct(private PDO $pdo)
    {
        $this->dao = new CovinhaDAO($pdo);
    }

    public function listarTabela(): array
    {
        return $this->dao->listarPerfisComUsuarios();
    }

    public function listarUsuarios(): array
    {
        return $this->dao->listarUsuarios();
    }

    private function deduzirDistribuicaoGenotipo(
        ?int $fenotipo,
        ?array $fenPais,
        array $avosPerfis,
        array $irmaosPerfis,
        string $campoCovinha
    ): array {
        if ($fenotipo === null) {
            return ['CC'=>0.25, 'Cc'=>0.5, 'cc'=>0.25];
        }

        if ($fenotipo === 0) {
            return ['CC'=>0.0, 'Cc'=>0.0, 'cc'=>1.0];
        }

        foreach ($irmaosPerfis as $irm) {
            $fi = $irm[$campoCovinha] ?? null;
            if ($fi === 0) {
                return ['CC'=>0.0, 'Cc'=>1.0, 'cc'=>0.0];
            }
        }

        if ($fenPais && ( ($fenPais['pai'] ?? 1) === 0 || ($fenPais['mae'] ?? 1) === 0 )) {
            return ['CC'=>0.0, 'Cc'=>1.0, 'cc'=>0.0];
        }

        foreach ($avosPerfis as $a) {
            if ($a === 0) {
                return ['CC'=>0.4, 'Cc'=>0.6, 'cc'=>0.0];
            }
        }

        return ['CC'=>0.5, 'Cc'=>0.5, 'cc'=>0.0];
    }

    private function gametas(array $dist): array
    {
        $pC = ($dist['CC'] ?? 0)*1.0 + ($dist['Cc'] ?? 0)*0.5 + ($dist['cc'] ?? 0)*0.0;
        return ['C' => $pC, 'c' => 1.0 - $pC];
    }

    private function cruzar(array $g1, array $g2): array
    {
        return [
            'CC' => $g1['C'] * $g2['C'],
            'Cc' => $g1['C'] * $g2['c'] + $g1['c'] * $g2['C'],
            'cc' => $g1['c'] * $g2['c'],
        ];
    }

    private function fenotipoFilho(array $gen): array
    {
        return [
            'sim' => ($gen['CC'] ?? 0) + ($gen['Cc'] ?? 0),
            'nao' => ($gen['cc'] ?? 0),
        ];
    }

    private function campo(?array $perfil, string $campo): ?int
    {
        if (!$perfil) return null;
        if (!array_key_exists($campo, $perfil)) return null;
        $v = $perfil[$campo];
        return $v === null ? null : (int)$v;
    }

    private function flattenAvos(array $avos, string $campo): array
    {
        $out = [];
        foreach (['paterno','materno'] as $lado) {
            foreach (['avo','avoh'] as $k) {
                $out[] = $this->campo($avos[$lado][$k] ?? null, $campo);
            }
        }
        return array_values(array_filter($out, fn($v) => $v === 0 || $v === 1));
    }

    public function calcular(int $idPai, int $idMae): array
    {
        $perfilPai = $this->dao->getPerfilByUsuario($idPai);
        $perfilMae = $this->dao->getPerfilByUsuario($idMae);

        if (!$perfilPai || !$perfilMae) {
            return ['erro' => 'Pai e Mãe precisam ter PERFIL cadastrado na tabela "perfil".'];
        }

        $paiQ = $this->campo($perfilPai, 'cov_queixo');
        $paiB = $this->campo($perfilPai, 'cov_bochecha');
        $maeQ = $this->campo($perfilMae, 'cov_queixo');
        $maeB = $this->campo($perfilMae, 'cov_bochecha');

        $avosPai = $this->dao->getAvosDeUsuario($idPai);
        $avosMae = $this->dao->getAvosDeUsuario($idMae);

        $irmaosPai = $this->dao->getIrmaos($idPai);
        $irmaosMae = $this->dao->getIrmaos($idMae);

        $paisDoPai = $this->dao->getPais($idPai);
        $paisDaMae = $this->dao->getPais($idMae);

        $fenPaisPaiQ = [
            'pai' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDoPai['id_pai'] ?? 0)), 'cov_queixo'),
            'mae' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDoPai['id_mae'] ?? 0)), 'cov_queixo'),
        ];
        $fenPaisMaeQ = [
            'pai' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDaMae['id_pai'] ?? 0)), 'cov_queixo'),
            'mae' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDaMae['id_mae'] ?? 0)), 'cov_queixo'),
        ];

        $fenPaisPaiB = [
            'pai' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDoPai['id_pai'] ?? 0)), 'cov_bochecha'),
            'mae' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDoPai['id_mae'] ?? 0)), 'cov_bochecha'),
        ];
        $fenPaisMaeB = [
            'pai' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDaMae['id_pai'] ?? 0)), 'cov_bochecha'),
            'mae' => $this->campo($this->dao->getPerfilByUsuario((int)($paisDaMae['id_mae'] ?? 0)), 'cov_bochecha'),
        ];

        $distPaiQ = $this->deduzirDistribuicaoGenotipo(
            $paiQ,
            $fenPaisPaiQ,
            $this->flattenAvos($avosPai, 'cov_queixo'),
            $irmaosPai,
            'cov_queixo'
        );
        $distMaeQ = $this->deduzirDistribuicaoGenotipo(
            $maeQ,
            $fenPaisMaeQ,
            $this->flattenAvos($avosMae, 'cov_queixo'),
            $irmaosMae,
            'cov_queixo'
        );
        $gPaiQ = $this->gametas($distPaiQ);
        $gMaeQ = $this->gametas($distMaeQ);
        $filhoQGen = $this->cruzar($gPaiQ, $gMaeQ);
        $filhoQFen = $this->fenotipoFilho($filhoQGen);

        $distPaiB = $this->deduzirDistribuicaoGenotipo(
            $paiB, $fenPaisPaiB,
            $this->flattenAvos($avosPai, 'cov_bochecha'),
            $irmaosPai, 'cov_bochecha'
        );
        $distMaeB = $this->deduzirDistribuicaoGenotipo(
            $maeB, $fenPaisMaeB,
            $this->flattenAvos($avosMae, 'cov_bochecha'),
            $irmaosMae, 'cov_bochecha'
        );
        $gPaiB = $this->gametas($distPaiB);
        $gMaeB = $this->gametas($distMaeB);
        $filhoBGen = $this->cruzar($gPaiB, $gMaeB);
        $filhoBFen = $this->fenotipoFilho($filhoBGen);

        return [
            'queixo' => [
                'pais' => ['pai' => $paiQ, 'mae' => $maeQ],
                'distPai' => $distPaiQ, 'distMae' => $distMaeQ,
                'filhoGen' => $filhoQGen, 'filhoFen' => $filhoQFen,
            ],
            'bochecha' => [
                'pais' => ['pai' => $paiB, 'mae' => $maeB],
                'distPai' => $distPaiB, 'distMae' => $distMaeB,
                'filhoGen' => $filhoBGen, 'filhoFen' => $filhoBFen,
            ]
        ];
    }
}
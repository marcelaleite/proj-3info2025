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
        $pC = ($dist['CC'] ?? 0)*1.0 + ($dist['Cc'] ?? 0)*0.5;
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
        $maeQ = $this->campo($perfilMae, 'cov_queixo');
        $distPaiQ = $this->deduzirDistribuicaoGenotipo($paiQ, null, [], [], 'cov_queixo');
        $distMaeQ = $this->deduzirDistribuicaoGenotipo($maeQ, null, [], [], 'cov_queixo');
        $filhoQGen = $this->cruzar($this->gametas($distPaiQ), $this->gametas($distMaeQ));
        $filhoQFen = $this->fenotipoFilho($filhoQGen);

        $paiB = $this->campo($perfilPai, 'cov_bochecha');
        $maeB = $this->campo($perfilMae, 'cov_bochecha');
        $distPaiB = $this->deduzirDistribuicaoGenotipo($paiB, null, [], [], 'cov_bochecha');
        $distMaeB = $this->deduzirDistribuicaoGenotipo($maeB, null, [], [], 'cov_bochecha');
        $filhoBGen = $this->cruzar($this->gametas($distPaiB), $this->gametas($distMaeB));
        $filhoBFen = $this->fenotipoFilho($filhoBGen);

        return [
            'queixo' => [
                'pais'     => ['pai' => $paiQ, 'mae' => $maeQ],
                'genPais'  => ['pai' => $distPaiQ, 'mae' => $distMaeQ],
                'filhoGen' => $filhoQGen,
                'filhoFen' => $filhoQFen
            ],
            'bochecha' => [
                'pais'     => ['pai' => $paiB, 'mae' => $maeB],
                'genPais'  => ['pai' => $distPaiB, 'mae' => $distMaeB],
                'filhoGen' => $filhoBGen,
                'filhoFen' => $filhoBFen
            ]
        ];
    }
}

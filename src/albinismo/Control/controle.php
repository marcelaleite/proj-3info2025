<?php
namespace App\Albinismo\Control;

use App\Albinismo\Model\AlbinismoDAO;
use PDO;

// Garante que o DAO seja carregado caso não esteja usando autoload do Composer
require_once __DIR__ . '/../Model/AlbinismoDAO.class.php';

class Controle {
    private AlbinismoDAO $dao;

    public function __construct(PDO $pdo)
    {
        // inicializa $dao assim que a classe é criada
        $this->dao = new AlbinismoDAO($pdo);
    }

    public function listarUsuarios(): array
    {
        return $this->dao->listUsuariosComPerfil();
    }

    public function listarTabela(): array
    {
        return $this->dao->listAll();
    }

    public function calcular(int $idPai, int $idMae): array
    {
        $perfilPai = $this->dao->getPerfilByUsuario($idPai);
        $perfilMae = $this->dao->getPerfilByUsuario($idMae);

        if (!$perfilPai || !$perfilMae) {
            return ['erro' => 'Pai ou mãe não possuem perfil cadastrado.'];
        }

        $distPai = $this->getDistribuicao($perfilPai);
        $distMae = $this->getDistribuicao($perfilMae);

        $filho = ['AA' => 0, 'Aa' => 0, 'aa' => 0];
        foreach ($distPai as $gPai => $pPai) {
            foreach ($distMae as $gMae => $pMae) {
                $p = $pPai * $pMae;
                foreach ($this->combinarAlelos($gPai, $gMae) as $gen) {
                    $filho[$gen] += $p * 0.5;
                }
            }
        }

        $fenotipo = [
            'albino' => $filho['aa'] ?? 0,
            'normal' => ($filho['AA'] ?? 0) + ($filho['Aa'] ?? 0)
        ];

        return [
            'pai'      => $distPai,
            'mae'      => $distMae,
            'filho'    => $filho,
            'fenotipo' => $fenotipo,
        ];
    }

    private function getDistribuicao(array $perfil): array
{
    if (!empty($perfil['albinismo']) && $perfil['albinismo'] == 1) {
        return ['AA' => 0, 'Aa' => 0, 'aa' => 1];
    }
    return ['AA' => 0.5, 'Aa' => 0.5, 'aa' => 0];
}


    private function combinarAlelos(string $gPai, string $gMae): array
    {
        $alelosPai = str_split($gPai);
        $alelosMae = str_split($gMae);

        $out = [];
        foreach ($alelosPai as $a) {
            foreach ($alelosMae as $m) {
                $gen = $a . $m;
                $out[] = $gen === 'aA' ? 'Aa' : $gen;
            }
        }
        return $out;
    }
}

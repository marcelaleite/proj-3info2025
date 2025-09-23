<?php
class AlbinismoRisco
{
    public static function probPortadorNaoAfetado(bool $albinoPaiOuMae, bool $avoAlbino, bool $irmaoAlbino, float $prior = 0.01): float
    {
        if ($albinoPaiOuMae) return 0.0;
        if ($avoAlbino) return 1.0;
        if ($irmaoAlbino) return 2.0 / 3.0;
        return $prior;
    }

    public static function riscoFilhoAa(float $pPai, float $pMae): float
    {
        return 0.25 * $pPai * $pMae;
    }

    public static function pct(float $p, int $dec = 2): string
    {
        return number_format($p * 100, $dec, ',', '.') . '%';
    }
}

// Exemplo de uso:
$pPai = AlbinismoRisco::probPortadorNaoAfetado(false, true, false); // Pai sem albinismo, avô albino
$pMae = AlbinismoRisco::probPortadorNaoAfetado(false, false, true); // Mãe sem albinismo, irmão albino
$riscoFilho = AlbinismoRisco::riscoFilhoAa($pPai, $pMae);

echo "Risco do filho: " . AlbinismoRisco::pct($riscoFilho);

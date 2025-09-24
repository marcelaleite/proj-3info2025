<?php
class Probabilidade {

    public function calcular($doenca, $paiTemDoenca, $maeTemDoenca) {
        
        if ($paiTemDoenca && $maeTemDoenca) {
            $prob = 75;
        } elseif ($paiTemDoenca || $maeTemDoenca) {
            $prob = 50;
        } else {
            $prob = 0;
        }

        return "Probabilidade de ter {$doenca}: {$prob}%";
    }
}
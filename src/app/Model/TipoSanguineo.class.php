<?php

class TipoSanguineo {

    public function calcularTipoSanguineo($pai_tipo, $mae_tipo) {
        $regras = [
            'A' => ['A' => ['A', 'O'], 'B' => ['A', 'B', 'AB', 'O'], 'AB' => ['A', 'B', 'AB'], 'O' => ['A', 'O']],
            'B' => ['A' => ['A', 'B', 'AB', 'O'], 'B' => ['B', 'O'], 'AB' => ['A', 'B', 'AB'], 'O' => ['B', 'O']],
            'AB' => ['A' => ['A', 'B', 'AB'], 'B' => ['A', 'B', 'AB'], 'AB' => ['A', 'B', 'AB'], 'O' => ['A', 'B']],
            'O' => ['A' => ['A', 'O'], 'B' => ['B', 'O'], 'AB' => ['A', 'B'], 'O' => ['O']]
        ];

        return $regras[$pai_tipo][$mae_tipo];
    }

    public function calcularFatorRh($pai_rh, $mae_rh) {
        if ($pai_rh === '+' && $mae_rh === '+') {
            return ['+', '-'];
        } elseif ($pai_rh === '+' && $mae_rh === '-') {
            return ['+', '-'];
        } elseif ($pai_rh === '-' && $mae_rh === '+') {
            return ['+', '-'];
        } else {
            return ['-'];
        }
    }
}
?>
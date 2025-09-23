<?php

class TipoSanguineo {

<<<<<<< HEAD
    public function calcularTipoSanguineo($pai_tipo, $mae_tipo) {
=======
    public function __construct($db) {
        $this->conexao = $db;
    }

    public function calcular($idUsuario) {
        // 1. Encontra o perfil do filho para obter os IDs dos pais
        $query_filho = "SELECT id_pai, id_mae FROM perfil WHERE usuario_idusuario = :id";
        $stmt_filho = $this->conexao->prepare($query_filho);
        $stmt_filho->bindParam(":id", $idUsuario, PDO::PARAM_INT);
        $stmt_filho->execute();
        $perfil_filho = $stmt_filho->fetch(PDO::FETCH_ASSOC);

        if (!$perfil_filho) {
            return "Usuário não encontrado ou perfil não cadastrado.";
        }

        if (empty($perfil_filho['id_pai']) || empty($perfil_filho['id_mae'])) {
            return "Dados de pai ou mãe não encontrados no perfil do usuário. Verifique se os pais estão associados corretamente.";
        }

        $idPai = $perfil_filho['id_pai'];
        $idMae = $perfil_filho['id_mae'];

        // 2. Função para obter as informações de sangue dos pais (tipo e fator)
        $get_parent_blood_info = function($id_parente) {
            $query = "SELECT tipo_sanguineo, fator FROM perfil WHERE usuario_idusuario = :id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $id_parente, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        };

        $pai_info = $get_parent_blood_info($idPai);
        $mae_info = $get_parent_blood_info($idMae);

        if (!$pai_info || !$mae_info || empty($pai_info['tipo_sanguineo']) || empty($mae_info['tipo_sanguineo'])) {
            return "Não foi possível encontrar o tipo sanguíneo do pai ou da mãe. Verifique se os perfis dos pais estão cadastrados e com tipo sanguíneo e fator definidos.";
        }

        // 3. Atribui as variáveis diretamente das novas colunas
        $pai_tipo = $pai_info['tipo_sanguineo']; // 'A', 'B', 'O', etc.
        $pai_rh = $pai_info['fator'];           // '+', '-'

        $mae_tipo = $mae_info['tipo_sanguineo'];
        $mae_rh = $mae_info['fator'];

        // 4. A lógica de cálculo permanece a mesma
>>>>>>> origin/PrevisãoTipoSanguíneo
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

    public function getUsuarios() {
        $query = "SELECT id_usuario, nome FROM usuario";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
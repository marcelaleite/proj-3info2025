<?php
require_once __DIR__ . "/../../DAO/Database.class.php";

class Probabilidade {

    public function calcular($idUsuario, $idDoenca) {
        $db = Database::getConexao(); // conexão PDO

        // Busca o id_pai e id_mae do usuário
        $sql = "SELECT id_pai, id_mae 
                  FROM perfil 
                 WHERE usuario_idusuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idUsuario]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return "Usuário sem perfil cadastrado.";
        }

        $idPai = $row['id_pai'];
        $idMae = $row['id_mae'];

        // Verifica se o pai tem a doença
        $paiTemDoenca = false;
        if ($idPai) {
            $sqlPai = "SELECT 1 
                         FROM perfil 
                        WHERE usuario_idusuario = ? 
                          AND doenca_genealogica = ?";
            $stmt = $db->prepare($sqlPai);
            $stmt->execute([$idPai, $idDoenca]);
            $paiTemDoenca = $stmt->fetch() ? true : false;
        }

        // Verifica se a mãe tem a doença
        $maeTemDoenca = false;
        if ($idMae) {
            $sqlMae = "SELECT 1 
                         FROM perfil 
                        WHERE usuario_idusuario = ? 
                          AND doenca_genealogica = ?";
            $stmt = $db->prepare($sqlMae);
            $stmt->execute([$idMae, $idDoenca]);
            $maeTemDoenca = $stmt->fetch() ? true : false;
        }

        // Cálculo da probabilidade
        if ($paiTemDoenca && $maeTemDoenca) {
            $prob = 75;
        } elseif ($paiTemDoenca || $maeTemDoenca) {
            $prob = 50;
        } else {
            $prob = 0;
        }

        return "Probabilidade de ter a doença (ID {$idDoenca}): {$prob}%";
    }
}

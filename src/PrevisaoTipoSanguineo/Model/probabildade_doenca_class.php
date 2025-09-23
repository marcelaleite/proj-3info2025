<?php
class Probabilidade {
    private $conexao;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function calcular($idUsuario, $doenca) {
        
        $query = "SELECT id_pai, id_mae FROM perfil WHERE usuario_idusuario = :id";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(":id", $idUsuario);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$perfil) {
            return "Usuário não encontrado.";
        }

        $idPai = $perfil['id_pai'];
        $idMae = $perfil['id_mae'];

        
        $queryPai = "SELECT doenca_genealogica FROM perfil WHERE usuario_idusuario = :id";
        $stmtPai = $this->conexao->prepare($queryPai);
        $stmtPai->bindParam(":id", $idPai);
        $stmtPai->execute();
        $pai = $stmtPai->fetch(PDO::FETCH_ASSOC);

    
        $queryMae = "SELECT doenca_genealogica FROM perfil WHERE usuario_idusuario = :id";
        $stmtMae = $this->conexao->prepare($queryMae);
        $stmtMae->bindParam(":id", $idMae);
        $stmtMae->execute();
        $mae = $stmtMae->fetch(PDO::FETCH_ASSOC);

        
        $temPai = ($pai && $pai['doenca_genealogica'] == $doenca);
        $temMae = ($mae && $mae['doenca_genealogica'] == $doenca);

        if ($temPai && $temMae) {
            $prob = 75;
        } elseif ($temPai || $temMae) {
            $prob = 50;
        } else {
            $prob = 0;
        }

        return "Probabilidade de ter {$doenca}: {$prob}%";
    }
}

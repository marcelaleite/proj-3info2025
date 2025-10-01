<?php
require_once "Database.class.php";  // classe de conexão/execução de queries

class Perfil {
    private $usuarioId;
    private $sexo;
    private $corOlho;
    private $corCabelo;
    private $tipoOrelha;
    private $tipoSanguineo;
    private $daltonismo;
    private $sardas;
    private $fator;
    private $covQueixo;
    private $covBochecha;
    private $albinismo;
    private $nacionalidade;
    private $doencaGenealogica; // id_doenca (FK)
    private $idPai;
    private $idMae;

    public function __construct($usuarioId, $sexo, $corOlho, $corCabelo, $tipoOrelha, $tipoSanguineo,
                                $daltonismo, $sardas, $fator, $covQueixo, $covBochecha,
                                $albinismo, $nacionalidade, $doencaGenealogica, $idPai, $idMae) {
        $this->usuarioId = $usuarioId;
        $this->sexo = $sexo;
        $this->corOlho = $corOlho;
        $this->corCabelo = $corCabelo;
        $this->tipoOrelha = $tipoOrelha;
        $this->tipoSanguineo = $tipoSanguineo;
        $this->daltonismo = $daltonismo;
        $this->sardas = $sardas;
        $this->fator = $fator;
        $this->covQueixo = $covQueixo;
        $this->covBochecha = $covBochecha;
        $this->albinismo = $albinismo;
        $this->nacionalidade = $nacionalidade;
        $this->doencaGenealogica = $doencaGenealogica;
        $this->idPai = $idPai;
        $this->idMae = $idMae;
    }

    // Inserir perfil no BD
    public function inserir(): bool {
        $sql = "INSERT INTO perfil 
                   (sexo, cor_olho, cor_cabelo, tipo_orelha, tipo_sanguineo, daltonismo, sardas, fator,
                    cov_queixo, cov_bochecha, albinismo, nacionalidade, doenca_genealogica,
                    usuario_idusuario, id_pai, id_mae)
                VALUES
                   (:sexo, :corOlho, :corCabelo, :tipoOrelha, :tipoSanguineo, :daltonismo, :sardas, :fator,
                    :covQueixo, :covBochecha, :albinismo, :nacionalidade, :doenca, :usuarioId, :idPai, :idMae)";

        $parametros = [
            ':sexo'        => $this->sexo,
            ':corOlho'     => $this->corOlho,
            ':corCabelo'   => $this->corCabelo,
            ':tipoOrelha'  => $this->tipoOrelha,
            ':tipoSanguineo' => $this->tipoSanguineo,
            ':daltonismo'  => $this->daltonismo,
            ':sardas'      => $this->sardas,
            ':fator'       => $this->fator,
            ':covQueixo'   => $this->covQueixo ? 1 : 0,
            ':covBochecha' => $this->covBochecha ? 1 : 0,
            ':albinismo'   => $this->albinismo ? 1 : 0,
            ':nacionalidade' => $this->nacionalidade,
            ':doenca'      => $this->doencaGenealogica,
            ':usuarioId'   => $this->usuarioId,
            ':idPai'       => $this->idPai,
            ':idMae'       => $this->idMae
        ];

        return Database::executar($sql, $parametros) === true;
    }
    // Alterar perfil no BD
    public function alterar(): bool {
        $sql = "UPDATE perfil SET
                    sexo = :sexo,
                    cor_olho = :corOlho,
                    cor_cabelo = :corCabelo,
                    tipo_orelha = :tipoOrelha,
                    tipo_sanguineo = :tipoSanguineo,
                    daltonismo = :daltonismo,
                    sardas = :sardas,
                    fator = :fator,
                    cov_queixo = :covQueixo,
                    cov_bochecha = :covBochecha,
                    albinismo = :albinismo,
                    nacionalidade = :nacionalidade,
                    doenca_genealogica = :doenca,
                    id_pai = :idPai,
                    id_mae = :idMae
                WHERE usuario_idusuario = :usuarioId";

        $parametros = [
            ':sexo'        => $this->sexo,
            ':corOlho'     => $this->corOlho,
            ':corCabelo'   => $this->corCabelo,
            ':tipoOrelha'  => $this->tipoOrelha,
            ':tipoSanguineo' => $this->tipoSanguineo,
            ':daltonismo'  => $this->daltonismo,
            ':sardas'      => $this->sardas,
            ':fator'       => $this->fator,
            ':covQueixo'   => $this->covQueixo ? 1 : 0,
            ':covBochecha' => $this->covBochecha ? 1 : 0,
            ':albinismo'   => $this->albinismo ? 1 : 0,
            ':nacionalidade' => $this->nacionalidade,
            ':doenca'      => $this->doencaGenealogica,
            ':idPai'       => $this->idPai,
            ':idMae'       => $this->idMae,
            ':usuarioId'   => $this->usuarioId
        ];

        return Database::executar($sql, $parametros) === true;
    }

    // Excluir perfil do BD
    public function excluir(): bool {
        $sql = "DELETE FROM perfil WHERE usuario_idusuario = :usuarioId";
        $parametros = [':usuarioId' => $this->usuarioId];
        return Database::executar($sql, $parametros) === true;
    }
}

?>
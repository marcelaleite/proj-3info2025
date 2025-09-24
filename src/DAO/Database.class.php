<?php
require_once(dirname(__FILE__) . '/../../config/config.inc.php');

class Database {
    private static $conexao = null;

    public static function getConexao() {
        if (self::$conexao === null) {
            try {
                self::$conexao = new PDO(DSN, USUARIO, SENHA);
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
                die();
            }
        }
        return self::$conexao;
    }

    public static function executar($sql, $parametros) {
        $comando = self::getConexao()->prepare($sql);
        foreach ($parametros as $chave => $valor) {
            $comando->bindValue($chave, $valor);
        }
        $comando->execute();
        return $comando;
    }
}
?>
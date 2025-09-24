<?php
require_once '../../cadastro_menu_login/Model/Classe.class.php';
class Daltonismo{
    private $id;
    private $tipo;
    private $nome; 

    // construtor da classe
    public function __construct($id,$nome,$tipo){
        $this->setId($id);
        $this->setNome($nome);
        $this->setTipo($tipo);
    }

}


?>
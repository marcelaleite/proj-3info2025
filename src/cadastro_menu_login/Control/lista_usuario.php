<?php
session_start();

require_once('../valida_login.php');
    require_once("../Model/Classe.class.php");
    $busca = isset($_GET['busca'])?$_GET['busca']:0;
    $tipo = isset($_GET['tipo'])?$_GET['tipo']:0;
   
    $lista = Usuario::listar($tipo, $busca);
    $itens = '';
    foreach($lista as $usuario){
        $item = file_get_contents('itens_listagem_usuarios.html');
        $item = str_replace('{id}',$usuario->getId(),$item);
        $item = str_replace('{nome}',$usuario->getNome(),$item);
        $item = str_replace('{email}',$usuario->getEmail(),$item);
        $item = str_replace('{telefone}',$usuario->getTelefone(),$item);
        $item = str_replace('{senha}',$usuario->getSenha(),$item);
        $item = str_replace('{confirmar}',$usuario->getConfirmar(),$item);
        $item = str_replace('{dt_nascimento}',$usuario->getDt_nascimento(),$item);
        $item = str_replace('{instituicao}',$usuario->getInstituicao(),$item);
        $item = str_replace('{descricao}',$usuario->getDescricao(),$item);
        $itens .= $item;
    }
    $listagem = file_get_contents('../View/listagem_usuario.html');
    $listagem = str_replace('{itens}',$itens,$listagem);
    print($listagem);
     
?>
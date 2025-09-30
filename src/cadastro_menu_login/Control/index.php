<?php
session_start();

require_once("../Model/Classe.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = isset($_POST['id'])?$_POST['id']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:"";
    $email = isset($_POST['email'])?$_POST['email']:"";
    $telefone = isset($_POST['telefone'])?$_POST['telefone']:"";
    $senha = isset($_POST['senha'])?$_POST['senha']:"";
    $confirmar = isset($_POST['confirmar'])?$_POST['confirmar']:"";
    $dt_nascimento = isset($_POST['dt_nascimento'])?$_POST['dt_nascimento']:"";
    $instituicao = isset($_POST['instituicao'])?$_POST['instituicao']:"";
    $descricao = isset($_POST['descricao'])?$_POST['descricao']:0;
    $acao = isset($_POST['acao'])?$_POST['acao']:"";

    $usuario = new Usuario($id,$nome,$email,$telefone,$senha,$confirmar,$dt_nascimento,$dt_nascimento,$instituicao,$descricao);
    if ($acao == 'salvar')
        if ($id > 0)
            $resultado = $usuario->alterar();
        else
            $resultado = $usuario->inserir();
    elseif ($acao == 'excluir')
        $resultado = $usuario->excluir();

    // if ($resultado)
    //     header("Location: index.php");
    // else
    //     echo "Erro ao salvar dados: ". $usuario;
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $formulario = file_get_contents(__DIR__ . '/../View/cadastro.html');

    $id = isset($_GET['id'])?$_GET['id']:0;
    $resultado = Usuario::listar(1,$id);
    if ($resultado){
        $usuario = $resultado[0];
        $formulario = str_replace('{id}',$usuario->getId(),$formulario);
        $formulario = str_replace('{nome}',$usuario->getNome(),$formulario);
        $formulario = str_replace('{email}',$usuario->getEmail(),$formulario);
        $formulario = str_replace('{telefone}',$usuario->getTelefone(),$formulario);
        $formulario = str_replace('{senha}',$usuario->getSenha(),$formulario);
        $formulario = str_replace('{confirmar}',$usuario->getConfirmar(),$formulario);
        $formulario = str_replace('{dt_nascimento}',$usuario->getDt_nascimento(),$formulario);
        $formulario = str_replace('{instituicao}',$usuario->getInstituicao(),$formulario);
        $formulario = str_replace('{descricao}',$usuario->getDescricao(),$formulario);

    }else{
        $formulario = str_replace('{id}',0,$formulario);
        $formulario = str_replace('{nome}','',$formulario);
        $formulario = str_replace('{email}','',$formulario);
        $formulario = str_replace('{telefone}','',$formulario);
        $formulario = str_replace('{senha}','',$formulario);
        $formulario = str_replace('{confirmar}','',$formulario);
        $formulario = str_replace('{dt_nascimento}','',$formulario);
        $formulario = str_replace('{instituicao}','',$formulario);
        $formulario = str_replace('{descricao}','',$formulario);
    }
    print($formulario); 
}
?>
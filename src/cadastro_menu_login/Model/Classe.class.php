<?php
require_once'../../DAO/Database.class.php';
class Usuario{
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $senha;
    private $confirmar;
    private $dt_nascimento;
    private $instituicao;
    private $descricao; // objeto login

    // construtor da classe
    public function __construct($id,$nome,$email,$telefone,$senha,$confirmar,$dt_nascimento,$instituicao,$descricao){
        $this->setId($id);
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setTelefone($telefone);
        $this->setSenha($senha);
        $this->setConfirmar($confirmar);
        $this->setDt_nascimento($dt_nascimento);
        $this->setInstituicao($instituicao);
        $this->setDescricao($descricao);
      //  $this->login->setIdSession(1);
    }

     public function setId($id){
        if ($id < 0)
            throw new Exception('Erro. O ID deve ser maior ou igual a 0');
        else
            $this->id = $id;
    }

    public function setNome($nome){
        if ($nome == "")
            throw new Exception('Erro. Informe um nome.');
        else
            $this->nome = $nome;
    }

    public function setEmail($email){
        $padrao = "/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if (($email == "") && !preg_match($padrao,strtolower($email)))
            throw new Exception('Erro. Informe um email.');
        else
            $this->email = $email;
    }

    public function setTelefone($telefone){
        if ($telefone == "")
            throw new Exception('Erro. Informe um telefone.');
        else
            $this->telefone = $telefone;
    }

    public function setSenha($senha){
        if ($senha == "") // regras para senha
            throw new Exception('Erro. Informe uma senha válida.');
        else
            $this->senha = $senha;
    }

    public function setConfirmar($confirmar){
        if ($confirmar == "")
            throw new Exception('Erro. Confirme sua senha.');
        else
            $this->confirmar = $confirmar;
    }

    public function setDt_nascimento($dt_nascimento){
        if ($dt_nascimento == "") // regras para dt_nascimento
            throw new Exception('Erro. Informe sua data de nascimento.');
        else
            $this->dt_nascimento = $dt_nascimento;
    }

    public function setInstituicao($instituicao){
        if ($instituicao == "") // regras para instituicao
            throw new Exception('Erro. Informe sua instituicao.');
        else
            $this->instituicao = $instituicao;
    }
    public function setDescricao($descricao){
        if ($descricao == "")
            throw new Exception('Erro. Descreva.');
        else
            $this->descricao = $descricao;
    }


    public function getId(){return $this->id;}
    public function getNome(){return $this->nome;}
    public function getEmail(){return $this->email;}
    public function getTelefone(){return $this->telefone;}
    public function getSenha(){return $this->senha;}
    public function getConfirmar(){return $this->confirmar;}
    public function getDt_nascimento(){return $this->dt_nascimento;}
    public function getInstituicao(){return $this->instituicao;}
    public function Descricao(){return $this->descricao;}

    // método mágico para imprimir uma atividade
    public function __toString():String{  
        $str = "Usuario: $this->getId() - $this->getNome() - $this->getEmail() - $this->getTelefone() - $this->getSenha() - $this->getConfirmar() - $this->getDt_nascimento() - $this->getInstituicao() - $this->getDescricao()";        
        return $str;
    }
    
    public function inserir():Bool{
        $sql = "INSERT INTO Usuario 
                    (nome, email, telefone, senha, confirmar, dt_nascimento, instituicao, descricao)
                    VALUES(:nome, :email, :telefone, :senha, :confirmar, :dt_nascimento, :instituicao, :descricao)";
        
        $parametros = array(':nome'=>$this->getNome(),
                            ':email'=>$this->getEmail(),
                            ':telefone'=>$this->getTelefone(),
                            ':senha'=>$this->getSenha(),
                            ':confirmar'=>$this->getConfirmar(),
                            ':dt_nascimento'=>$this->getDt_nascimento(),
                            ':instituicao'=>$this->getInstituicao(),
                            ':descricao'=>$this->getDescricao());
        
        return Database::executar($sql, $parametros) == true;
    }

    public static function listar($tipo=0, $info=''):Array{
        $sql = "SELECT * FROM usuario";
        switch ($tipo){
            case 0: break;
            case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
            case 2: $sql .= " WHERE matricula = :info ORDER BY id"; break; // filtro por matricula
            case 3: $sql .= " WHERE nome like :info ORDER BY descricao"; $info = '%'.$info.'%'; break; // filtro por descrição
        }
        $parametros = array();
        if ($tipo > 0)
            $parametros = [':info'=>$info];

        $comando = Database::executar($sql, $parametros);
        $usuarios = [];
        while ($registro = $comando->fetch()){
            $usuario = new Usuario($registro['id'],$registro['nome'],$registro['email'],$registro['senha'],$registro['matricula'],$registro['contato']);
            array_push($usuarios,$usuario);
        }
        return $usuarios;
    }

    public function alterar():Bool{       
    $sql = "UPDATE usuario
                SET nome = :nome, 
                    email = :email,
                    senha = :senha,
                    matricula = :matricula,
                    contato = :contato
                WHERE id = :id";
        $parametros = array(':id'=>$this->getId(),
                        ':nome'=>$this->getNome(),
                        ':email'=>$this->getEmail(),
                        ':senha'=>$this->getSenha(),
                        ':matricula'=>$this->getMatricula(),
                        ':contato'=>$this->getContato());
        return Database::executar($sql, $parametros) == true;
    }

    public function excluir():Bool{
        $sql = "DELETE FROM usuario
                    WHERE id = :id";
        $parametros = array(':id'=>$this->getid());
        return Database::executar($sql, $parametros) == true;
    }

}


?>
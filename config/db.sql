-- CRIA O BANCO
CREATE SCHEMA IF NOT EXISTS BioLineage;
USE BioLineage;

-- ==========================
-- TABELA USUÁRIO
-- ==========================
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  email VARCHAR(45) NULL,
  telefone VARCHAR(45) NULL,
  senha VARCHAR(255) NULL,
  dataNascimento DATE NULL,
  instituicao VARCHAR(45) NULL,
  descricao VARCHAR(45) NULL
);

-- ==========================
-- TABELA DOENÇA
-- ==========================
CREATE TABLE IF NOT EXISTS doenca (
  id_doenca INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL
);

-- Inserindo pelo menos 1 doença para evitar erro de FK
INSERT INTO doenca (nome) VALUES ('Nenhuma');

--  dataNascimento date NULL,
--  Instituicao VARCHAR(45) NULL,
--  descricao VARCHAR(45) NULL);

-- ==========================
-- TABELA PERFIL
-- ==========================
CREATE TABLE IF NOT EXISTS perfil (
  id_perfil INT PRIMARY KEY AUTO_INCREMENT,
  sexo ENUM('Feminino','Masculino','Não Binário','Outro') NOT NULL,
  cor_olho ENUM('Azul','Castanho','Cinza','Preto','Verde') NOT NULL,
  cor_cabelo ENUM('Branco','Castanho','Loiro','Preto','Ruivo') NOT NULL,
  tipo_orelha ENUM('Com divisão','Sem divisão') NULL,
  tipo_sanguineo ENUM('A','B','AB','O') NULL,
  daltonismo ENUM('Sim','Não') NULL,
  sardas ENUM('Sim','Não') NULL,
  fator ENUM('+','-') NULL,
  cov_queixo TINYINT NULL,
  cov_bochecha TINYINT NULL,
  albinismo TINYINT NULL,
  nacionalidade VARCHAR(45) NULL,
  doenca_genealogica INT NOT NULL,
  usuario_idusuario INT NOT NULL,
  id_pai INT NOT NULL,
  id_mae INT NOT NULL,
  alelo_pai VARCHAR(45),
  alelo_mae VARCHAR(45)
  );

-- dados de teste
    insert into usuario values(null, 'Nome teste','emailteste@mail.com', '12345678','123','2000-06-23','IFC','usuario teste');
    insert into usuario values(null, 'Pai teste','paiteste@mail.com', '12345678','123','1975-01-15','IFC','usuario teste');
    insert into usuario values(null, 'Mae teste','maeteste@mail.com', '12345678','123','1980-05-25','IFC','usuario teste');
    insert into perfil values(null, 'Castanho','Castanho','Sem divisão','A','+',0,0,'Brasileira',0,1,2,3); -- perfil do teste
    
	insert into usuario values(null, 'Mãe Mãe teste','maemaeteste@mail.com', '12345678','123','1975-01-15','IFC','usuario teste');
    insert into usuario values(null, 'Pai Mãe teste','paimaeteste@mail.com', '12345678','123','1975-01-15','IFC','usuario teste');
    insert into usuario values(null, 'Mãe Pai teste','maepaiteste@mail.com', '12345678','123','1975-01-15','IFC','usuario teste');
    insert into usuario values(null, 'Pai Pai teste','paipaiteste@mail.com', '12345678','123','1975-01-15','IFC','usuario teste');
    

    insert into perfil values(null, 'Azul','Loiro','Sem divisão','A','+',0,0,0,'Brasileira',0,2,7,6); -- perfil do pai
    insert into perfil values(null, 'Castanho','Castanho','Sem divisão','A','+',0,0,0,'Brasileira',0,3,5,4); -- perfil do mae
-- consulta 
 select u.id_usuario,
        u.nome,
        pe.id_perfil ,
        (select m.nome from usuario m where m.id_usuario = pe.id_mae) as Mae,
        (select p.nome from usuario p where p.id_usuario = pe.id_pai) as Pai
   from usuario u
   inner join perfil pe on (pe.usuario_idusuario = u.id_usuario);

-- outro exemplo - dessa forma fica mais fácio carregar mais dados com menor qtd de transações
 select u.id_usuario,
        u.nome,
        pe.id_perfil ,
        mae.nome as mae,
        mae.doenca_genealogica, 
        pai.nome as pai
   from usuario u
   inner join perfil pe on (pe.usuario_idusuario = u.id_usuario)
   left outer join usuario mae on (mae.id_usuario = pe.id_mae);
   left outer join usuario mae on (mae.id_usuario = pe.id_mae)
   left outer join usuario pai on (pai.id_usuario = pe.id_pai)
   left outer join doenca dm on (d.id_doenca = mae.doenca_genealogica)
   left outer join doenca dp on (d.id_doenca = pai.doenca_genealogica)
   where u.id_usuario = 1;

CREATE TABLE cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL
);

select 
        mae.nome as mae,
        pai.nome as pai
   from usuario u
   inner join perfil pe on (pe.usuario_idusuario = u.id_usuario)
   left outer join usuario mae on (mae.id_usuario = pe.id_mae);
   left outer join usuario pai on (pai.id_usuario = pe.id_pai);
create schema BioLineage;
use BioLineage;

CREATE TABLE IF NOT EXISTS usuario (
  id INT PRIMARY KEY  auto_increment,
  nome VARCHAR(45) NOT NULL,
  email VARCHAR(45) NULL,
  telefone VARCHAR(45) NULL,
  senha VARCHAR(45) NULL,
  confirmar VARCHAR(45) NULL,
  dt_nascimento date NULL,
  instituicao VARCHAR(45) NULL,
  descricao VARCHAR(45) NULL);

CREATE TABLE IF NOT EXISTS perfil (
  sexo ENUM('Feminino','Masculino','Não Binário','Outro') NOT NULL,
  id_perfil INT  PRIMARY KEY auto_increment,
  cor_olho ENUM('Azul','Castanho','Cinza','Preto','Verde') NOT NULL,
  cor_cabelo ENUM('Branco','Castanho','Loiro','Preto','Ruivo') NOT NULL,
  tipo_orelha ENUM ('Com divisão','Sem divisão') NULL,
  tipo_sanguineo ENUM('A','B','AB','O') NULL,
  daltonismo ENUM('Sim','Não') NULL,
  sardas ENUM('Sim','Não') NULL,
  fator ENUM('+','-') NULL,
  cov_queixo tinyint NULL,
  cov_bochecha tinyint NULL,
  albinismo tinyint NULL,
  nacionalidade VARCHAR(45) NULL,
  doenca_genealogica int NOT NULL,
  usuario_idusuario INT NOT NULL,
  id_pai INT NOT NULL,
  id_mae INT NOT NULL,
    FOREIGN KEY (usuario_idusuario) REFERENCES usuario (id_usuario) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (id_pai) REFERENCES usuario (id_usuario) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (id_mae) REFERENCES usuario (id_usuario) ON DELETE NO ACTION ON UPDATE NO ACTION);
    FOREIGN KEY (doenca_genealogica) REFERENCES doenca (id_doenca) ON DELETE NO ACTION ON UPDATE NO ACTION;

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

CREATE TABLE IF NOT EXISTS doenca (
  id_doenca INT PRIMARY KEY auto_increment,
  nome VARCHAR(45) NOT NULL);   
   

-- outro exemplo - dessa forma fica mais fácio carregar mais dados com menor qtd de transações
 select u.id_usuario,
        u.nome,
        pe.id_perfil ,
        mae.nome as mae,
        pai.nome as pai
   from usuario u
   inner join perfil pe on (pe.usuario_idusuario = u.id_usuario)
   left outer join usuario mae on (mae.id_usuario = pe.id_mae);
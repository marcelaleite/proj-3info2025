CREATE SCHEMA IF NOT EXISTS BioLineage;
USE BioLineage;

-- ======== TABELA DE DOENÇAS ========
CREATE TABLE IF NOT EXISTS doenca (
  id_doenca INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL
);

-- ======== TABELA DE USUÁRIOS ========
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  email VARCHAR(45),
  telefone VARCHAR(45),
  senha VARCHAR(45),
  dataNascimento DATE,
  instituicao VARCHAR(45),
  descricao VARCHAR(100)
);

-- ======== TABELA DE PERFIL ========
CREATE TABLE IF NOT EXISTS perfil (
  id_perfil INT PRIMARY KEY AUTO_INCREMENT,
  sexo ENUM('Feminino','Masculino','Não Binário','Outro') NOT NULL,
  cor_olho ENUM('Azul','Castanho','Cinza','Preto','Verde') NOT NULL,
  cor_cabelo ENUM('Branco','Castanho','Loiro','Preto','Ruivo') NOT NULL,
  tipo_orelha ENUM('Com divisão','Sem divisão'),
  tipo_sanguineo ENUM('A','B','AB','O'),
  fator ENUM('+','-'),
  daltonismo ENUM('Sim','Não'),
  sardas ENUM('Sim','Não'),
  cov_queixo TINYINT DEFAULT 0,
  cov_bochecha TINYINT DEFAULT 0,
  albinismo TINYINT DEFAULT 0,
  nacionalidade VARCHAR(45),
  doenca_genealogica VARCHAR(45), -- permanece texto
  usuario_idusuario INT NOT NULL,
  id_pai INT,
  id_mae INT,
  alelo_pai VARCHAR(45),
  alelo_mae VARCHAR(45),

  FOREIGN KEY (usuario_idusuario) REFERENCES usuario (id_usuario),
  FOREIGN KEY (id_pai) REFERENCES usuario (id_usuario),
  FOREIGN KEY (id_mae) REFERENCES usuario (id_usuario)
  -- Removido o FOREIGN KEY de doenca_genealogica
);

-- ======== INSERÇÕES DE TESTE ========
INSERT INTO usuario (nome, email, telefone, senha, dataNascimento, instituicao, descricao)
VALUES
  ('Nome teste','emailteste@mail.com','12345678','123','2000-06-23','IFC','usuario teste'),
  ('Pai teste','paiteste@mail.com','12345678','123','1975-01-15','IFC','usuario teste'),
  ('Mae teste','maeteste@mail.com','12345678','123','1980-05-25','IFC','usuario teste');

INSERT INTO doenca (nome) VALUES ('Nenhuma'), ('ExemploDoenca');

INSERT INTO perfil (
  sexo, cor_olho, cor_cabelo, tipo_orelha, tipo_sanguineo, fator,
  daltonismo, sardas, cov_queixo, cov_bochecha, albinismo,
  nacionalidade, doenca_genealogica, usuario_idusuario, id_pai, id_mae,
  alelo_pai, alelo_mae
) VALUES (
  'Masculino','Castanho','Castanho','Sem divisão','A','+',
  'Não','Não',0,0,0,'Brasileira','ExemploDoenca',1,2,3,
  'Aa','Aa'
);

CREATE DATABASE IF NOT EXISTS sistema;
USE sistema;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sardas (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    pai_id INT NULL,
    mae_id INT NULL,
    sarda ENUM('sim','nao') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_pai_id (pai_id),
    INDEX idx_mae_id (mae_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (pai_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    FOREIGN KEY (mae_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

CREATE DATABASE IF NOT EXISTS Ecotire
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE Ecotire;

-- ================= USUARIO =================
CREATE TABLE if not exists usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE, 
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    tipo ENUM('cliente','admin') DEFAULT 'cliente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP 
) ENGINE=InnoDB;

-- ================= ENDERECO =================
CREATE TABLE if not exists endereco (
    id_endereco INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    cep VARCHAR(9) NOT NULL,
    numero INT NOT NULL,
    complemento VARCHAR(50),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= PRODUTOS =================
CREATE TABLE if not exists produtos (
    id_produto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,s
    preco DECIMAL(10,2) NOT NULL,
    promocao enum ('sem','com') default 'sem',
    promo int,
    descricao text,
    imagem MEDIUMBLOB,
    estoque INT DEFAULT 0
) ENGINE=InnoDB;

-- ================= CARRINHO =================
CREATE TABLE if not exists carrinho(
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    -- AQUI: Se o usuário ou o produto sumirem, limpa o carrinho
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= PEDIDOS =================
CREATE TABLE if not exists pedidos (
    id_pedido INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,     
    id_endereco_entrega INT NOT NULL,
    quantidade INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente','pago','enviado','entregue','cancelado') DEFAULT 'pendente',
    total DECIMAL(10,2) NOT NULL,
    metodo_pagamento VARCHAR(50),
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_endereco_entrega) REFERENCES endereco(id_endereco) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= AVALIACOES =================
CREATE TABLE if not exists avaliacoes (
    id_avaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    nota INT NOT NULL,
    comentario TEXT, 
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE, 
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    CHECK (nota BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- ================= EMAIL (CONTATO) =================
CREATE TABLE if not exists email (
    id_email INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    msg TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

-- ================= Favoritos =================
CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    UNIQUE KEY uk_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

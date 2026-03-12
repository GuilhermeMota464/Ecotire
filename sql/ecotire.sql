CREATE DATABASE IF NOT EXISTS Ecotire
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE Ecotire;

-- ================= USUARIO =================
CREATE TABLE if not exists usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE, -- Adicionado UNIQUE para evitar emails duplicados
    senha VARCHAR(255) NOT NULL, -- Aumentado para suportar hashes de senha (como Bcrypt)
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
-- Corrigido: Removido a coluna 'avaliacao' física, pois notas devem vir da tabela avaliacoes
CREATE TABLE if not exists produtos (
    id_produto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL, -- Aumentado de 30 para 100 caracteres
    preco DECIMAL(10,2) NOT NULL,
    promocao enum ('sem','com') default 'sem',
    promo_valor int,
    imagem varchar (200) NOT NULL,
    estoque INT DEFAULT 0
) ENGINE=InnoDB;

-- ================= CARRINHO =================
CREATE TABLE if not exists carrinho(
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
) ENGINE=InnoDB;

-- ================= PEDIDOS =================
-- CORREÇÃO AQUI: O erro estava no nome da coluna 'id_prudoto' e na FK faltante
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
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto), -- Agora referencia a coluna certa
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_endereco_entrega) REFERENCES endereco(id_endereco)
) ENGINE=InnoDB;

-- ================= AVALIACOES =================
CREATE TABLE if not exists avaliacoes (
    id_avaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    nota INT NOT NULL,
    comentario TEXT, -- Mudado para TEXT para maior flexibilidade
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
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

-- ================= USUARIO =================
INSERT INTO usuario (email, senha, telefone, tipo)
VALUES ('teste@email.com', '$2y$10$hashfakebcrypt', '11999999999', 'cliente');

-- ================= ENDERECO =================
INSERT INTO endereco (id_usuario, cep, numero, complemento)
VALUES (1, '12345-678', 100, 'Casa');

-- ================= PRODUTO =================
INSERT INTO produtos (nome, preco, promocao, promo_valor, imagem, estoque)
VALUES ('Estojo', 350.00, 'com', 299.99, 'pneu15.jpg', 50);

-- ================= CARRINHO =================
INSERT INTO carrinho (id_usuario, id_produto, quantidade, preco_unitario)
VALUES (1, 1, 2, 299.99);

-- ================= PEDIDOS =================
INSERT INTO pedidos 
(id_usuario, id_produto, id_endereco_entrega, quantidade, total, metodo_pagamento, preco_unitario)
VALUES 
(1, 1, 1, 2, 599.98, 'cartao_credito', 299.99);

-- ================= AVALIACOES =================
INSERT INTO avaliacoes (id_usuario, id_produto, nota, comentario)
VALUES (1, 1, 5, 'Produto excelente, recomendo!');

-- ================= EMAIL =================
INSERT INTO email (id_usuario, msg)
VALUES (1, 'Gostaria de saber mais sobre a garantia do produto.');

SELECT * FROM usuario;
SELECT * FROM endereco;
SELECT * FROM produtos;
SELECT * FROM carrinho;
SELECT * FROM pedidos;
SELECT * FROM avaliacoes;
SELECT * FROM email;

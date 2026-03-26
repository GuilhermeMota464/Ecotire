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
    -- AQUI: Se o usuário sumir, o endereço some
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
    -- AQUI: Relacionamentos com cascata
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
    comentario TEXT, -- Mudado para TEXT para maior flexibilidade
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

-- 1. Cadastra o Usuário
INSERT INTO usuario (email, senha, telefone, tipo) 
VALUES ('us@ecotire.com', MD5('senha123'), '11999998888', 'cliente');

-- 2. Cadastra o Endereço
INSERT INTO endereco (cep, numero, complemento) 
VALUES ('12200-000', 150, 'Apto 10');

-- 3. Cadastra o Produto
INSERT INTO produtos (nome, preco, promocao, promo_valor, estoque) 
VALUES ('Estojo', 350.00, 'sem', 0, 100);

-- 4. Cadastra o Pedido
INSERT INTO pedidos (quantidade, total, metodo_pagamento, preco_unitario, status)
VALUES (2, 700.00, 'PIX', 350.00, 'pago');

-- 5. Cadastra a Avaliação
INSERT INTO avaliacoes (nota, comentario)
VALUES (5, 'Entrega rápida e produto de qualidade!');

-- 6. Cadastra o Favorito
INSERT INTO favoritos (data_adicionado)
VALUES (CURRENT_TIMESTAMP);

-- 7. Mensagem de Email
INSERT INTO email (msg) 
VALUES ('Gostaria de saber o prazo de entrega para o meu CEP.');

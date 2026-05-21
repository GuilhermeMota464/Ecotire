CREATE DATABASE IF NOT EXISTS Aruana
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE Aruana;

-- ================= USUÁRIO =================
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================= ENDEREÇO =================
CREATE TABLE IF NOT EXISTS endereco (
    id_endereco INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    cep VARCHAR(9) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= PRODUTOS =================
CREATE TABLE IF NOT EXISTS produtos (
    id_produto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco_custo DECIMAL(10,2) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) NULL,
    modelo VARCHAR(50) NOT NULL,
    estoque INT DEFAULT 0 NOT NULL,
    imagem MEDIUMBLOB,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- ================= CARRINHO =================
CREATE TABLE IF NOT EXISTS carrinho (
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    UNIQUE KEY uk_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

-- ================= PEDIDOS =================
CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_endereco_entrega INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'pago', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_endereco_entrega) REFERENCES endereco(id_endereco) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ================= ITENS DO PEDIDO =================
CREATE TABLE IF NOT EXISTS pedido_itens (
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ================= PAGAMENTOS =================
CREATE TABLE IF NOT EXISTS pagamentos (
    id_pagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    metodo ENUM('PIX', 'CARTAO', 'BOLETO') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'aprovado', 'recusado', 'cancelado', 'reembolsado') DEFAULT 'pendente',
    codigo_transacao VARCHAR(100),
    data_pagamento DATETIME NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= AVALIAÇÕES =================
CREATE TABLE IF NOT EXISTS avaliacoes (
    id_avaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= FAVORITOS =================
CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    UNIQUE KEY uk_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

-- ================= CONTATO / MENSAGENS =================
CREATE TABLE IF NOT EXISTS contato (
    id_contato INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;
-- 1. Cadastra o Usuário
INSERT INTO usuario (email, senha, telefone, tipo) 
VALUES ('ge@ecotire.com', MD5('senha123'), '11999998888', 'cliente');

-- 2. Cadastra o Endereço
INSERT INTO endereco (cep, numero, complemento) 
VALUES ('12200-000', 150, 'Apto 10');

-- 3. Cadastra o Produto
INSERT INTO produtos (nome, preco, promocao, promo_valor, estoque) 
VALUES ('Estojin', 350.00, 'sem', 0, 100);

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

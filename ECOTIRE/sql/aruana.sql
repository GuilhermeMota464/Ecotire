CREATE DATABASE IF NOT EXISTS Aruana
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE Aruana;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    genero ENUM('Masculino', 'Feminino', 'Prefiro_nao_dizer', 'Outros') NOT NULL DEFAULT 'Prefiro_nao_dizer',
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS endereco (
    id_endereco INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    cep VARCHAR(9) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produtos (
    id_produto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco_custo DECIMAL(10,2) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) NULL,
    modelo VARCHAR(50) NOT NULL,
    estoque INT DEFAULT 0 NOT NULL,
    imagem MEDIUMBLOB,
    descricao VARCHAR(200) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

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

CREATE TABLE IF NOT EXISTS pedido_itens (
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pagamentos (
    id_pagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    metodo ENUM('PIX', 'CARTAO', 'BOLETO') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    Condicao ENUM('pendente', 'aprovado', 'recusado', 'cancelado', 'reembolsado') DEFAULT 'pendente',
    codigo_transacao VARCHAR(100),
    data_pagamento DATETIME NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE
) ENGINE=InnoDB;

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

CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE,
    UNIQUE KEY uk_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contato (
    id_contato INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO usuario (nome, email, senha, telefone, genero, tipo) VALUES
('Aruana Admin', 'contato@aruanaeco.com.br', '$2b$12$K8j7H6g5F4d3S2a1...', '11912345678', 'Prefiro_nao_dizer', 'admin'),
('Lucas Mendes', 'lucas.mendes@eco.com', '$2b$12$XyZ1v2w3u4t5s6r7...', '11988887777', 'Masculino', 'cliente'),
('Marina Silva', 'marina.verde@email.com', '$2b$12$AbC9876543210zyx...', '21977776666', 'Feminino', 'cliente');

INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES
(2, '05422-000', '450', 'Apto 12B - Pinheiros'),
(3, '22210-030', '88', 'Casa - Catete');

INSERT INTO produtos (nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, descricao, ativo) VALUES
('Escova de Dente de Bambu', 2.50, 12.90, 9.90, 'BioBrush Adulto', 120, 'Escova de dente 100% biodegradável com cerdas de carvão ativado.', TRUE),
('Copo Retrátil de Silicone', 8.00, 35.00, NULL, 'EcoCup 350ml', 45, 'Copo dobrável e reutilizável para bebidas quentes ou frias.', TRUE),
('Kit Canudo de Inox', 5.50, 19.90, 15.90, 'EcoStraw Trio', 60, 'Kit com 2 canudos de aço inoxidável e 1 escova de limpeza.', TRUE),
('Sabão Ecológico de Coco', 1.80, 7.50, NULL, 'TerraNat 200g', 0, 'Sabão em barra artesanal, livre de químicos agressivos.', TRUE);

INSERT INTO carrinho (id_usuario, id_produto, quantidade) VALUES
(2, 2, 2),
(2, 3, 1);

INSERT INTO pedidos (id_usuario, id_endereco_entrega, status, total) VALUES
(3, 2, 'pago', 45.60);

INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES
(1, 1, 3, 9.90),
(1, 3, 1, 15.90);

INSERT INTO pagamentos (id_pedido, metodo, valor, Condicao, codigo_transacao, data_pagamento) VALUES
(1, 'PIX', 45.60, 'aprovado', 'ECOMUNDO9876543210', NOW());

INSERT INTO avaliacoes (id_usuario, id_produto, nota, comentario) VALUES
(3, 1, 5, 'Excelente! As cerdas são macias e o cabo não mofa se secar direitinho.');

INSERT INTO favoritos (id_usuario, id_produto) VALUES
(2, 4);

INSERT INTO contato (id_usuario, mensagem) VALUES
(2, 'Olá! Vocês aceitam embalagens de volta para logística reversa e reciclagem?');

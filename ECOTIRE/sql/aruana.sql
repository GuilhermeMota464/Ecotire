
CREATE DATABASE IF NOT EXISTS Aruana
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE Aruana;

-- ==========================================
-- TABELA: USUARIO
-- ==========================================

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    genero ENUM(
        'Masculino',
        'Feminino',
        'Prefiro_nao_dizer',
        'Outros'
    ) NOT NULL DEFAULT 'Prefiro_nao_dizer',
    tipo ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: ENDERECO
-- ==========================================

CREATE TABLE IF NOT EXISTS endereco (
    id_endereco INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    cep VARCHAR(9) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: PRODUTOS
-- ==========================================

CREATE TABLE IF NOT EXISTS produtos (
    id_produto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco_custo DECIMAL(10,2) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) NULL,
    modelo VARCHAR(50) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    imagem MEDIUMBLOB,
    descricao VARCHAR(200) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    CHECK (preco_custo >= 0),
    CHECK (preco_venda >= 0),
    CHECK (preco_promocional IS NULL OR preco_promocional >= 0),
    CHECK (estoque >= 0)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: CARRINHO
-- ==========================================

CREATE TABLE IF NOT EXISTS carrinho (
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE CASCADE,

    UNIQUE KEY uk_carrinho_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: PEDIDOS
-- ==========================================

CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_endereco_entrega INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM(
        'pendente',
        'pago',
        'enviado',
        'entregue',
        'cancelado'
    ) NOT NULL DEFAULT 'pendente',
    total DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_endereco_entrega)
        REFERENCES endereco(id_endereco)
        ON DELETE RESTRICT,

    CHECK (total >= 0)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: PEDIDO_ITENS
-- ==========================================

CREATE TABLE IF NOT EXISTS pedido_itens (
    id_item INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    preco_unitario DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (id_pedido)
        REFERENCES pedidos(id_pedido)
        ON DELETE CASCADE,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE RESTRICT,

    CHECK (preco_unitario >= 0)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: PAGAMENTOS
-- ==========================================

CREATE TABLE IF NOT EXISTS pagamentos (
    id_pagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    metodo ENUM('PIX', 'CARTAO', 'BOLETO') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    condicao ENUM(
        'pendente',
        'aprovado',
        'recusado',
        'cancelado',
        'reembolsado'
    ) NOT NULL DEFAULT 'pendente',
    codigo_transacao VARCHAR(100),
    data_pagamento DATETIME NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_pedido)
        REFERENCES pedidos(id_pedido)
        ON DELETE CASCADE,

    CHECK (valor >= 0)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: AVALIACOES
-- ==========================================

CREATE TABLE IF NOT EXISTS avaliacoes (
    id_avaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: FAVORITOS
-- ==========================================

CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    data_adicionado DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE CASCADE,

    UNIQUE KEY uk_favoritos_usuario_produto (id_usuario, id_produto)
) ENGINE=InnoDB;

-- ==========================================
-- TABELA: CONTATO
-- ==========================================

CREATE TABLE IF NOT EXISTS contato (
    id_contato INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB;


-- ==========================================
-- INSERÇÃO DE USUÁRIOS
-- ==========================================

INSERT INTO usuario
(nome, email, senha, telefone, genero, tipo)
VALUES
('Aruana Admin', 'contato@aruanaeco.com.br', '', '00000000000', 'Prefiro_nao_dizer', 'admin'),
('Lucas Mendes', 'lucas.mendes@eco.com', '', '11988887777', 'Masculino', 'cliente'),
('Marina Silva', 'marina.verde@email.com', '', '21977776666', 'Feminino', 'cliente');


-- ==========================================
-- INSERÇÃO DE ENDEREÇOS
-- ==========================================

INSERT INTO endereco
(id_usuario, cep, numero, complemento)
VALUES
(2, '05422-000', '450', 'Apto 12B - Pinheiros'),
(3, '22210-030', '88', 'Casa - Catete');


-- ==========================================
-- INSERÇÃO DE PRODUTOS
-- ==========================================

INSERT INTO produtos
(nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, imagem, descricao, ativo)
VALUES

(
    'Escova de Dente de Bambu',
    2.50,
    12.90,
    9.90,
    'BioBrush Adulto',
    120,
    LOAD_FILE('C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/image (3).png'),
    'Escova de dente 100% biodegradável com cerdas de carvão ativado.',
    TRUE
),

(
    'Copo Retrátil de Silicone',
    8.00,
    35.00,
    NULL,
    'EcoCup 350ml',
    45,
    LOAD_FILE('C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/copo silicone.png'),
    'Copo dobrável e reutilizável para bebidas quentes ou frias.',
    TRUE
),

(
    'Kit Canudo de Inox',
    5.50,
    19.90,
    15.90,
    'EcoStraw Trio',
    60,
    LOAD_FILE('C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/canudo inox.webp'),
    'Kit com 2 canudos de aço inoxidável e 1 escova de limpeza.',
    TRUE
),

(
    'Sabão Ecológico de Coco',
    1.80,
    7.50,
    NULL,
    'TerraNat 200g',
    0,
    LOAD_FILE('C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/image (2).png'),
    'Sabão em barra artesanal, livre de químicos agressivos.',
    TRUE
);


-- ==========================================
-- INSERÇÃO NO CARRINHO
-- ==========================================

INSERT INTO carrinho
(id_usuario, id_produto, quantidade)
VALUES
(2, 2, 2),
(2, 3, 1);


-- ==========================================
-- INSERÇÃO DE PEDIDOS
-- ==========================================

INSERT INTO pedidos
(id_usuario, id_endereco_entrega, status, total)
VALUES
(3, 2, 'pago', 45.60);


-- ==========================================
-- INSERÇÃO DOS ITENS DO PEDIDO
-- ==========================================

INSERT INTO pedido_itens
(id_pedido, id_produto, quantidade, preco_unitario)
VALUES
(1, 1, 3, 9.90),
(1, 3, 1, 15.90);


-- ==========================================
-- INSERÇÃO DE PAGAMENTOS
-- ==========================================

INSERT INTO pagamentos
(id_pedido, metodo, valor, condicao, codigo_transacao, data_pagamento)
VALUES
(1, 'PIX', 45.60, 'aprovado', 'ECOMUNDO9876543210', NOW());


-- ==========================================
-- INSERÇÃO DE AVALIAÇÕES
-- ==========================================

INSERT INTO avaliacoes
(id_usuario, id_produto, nota, comentario)
VALUES
(3, 1, 5, 'Excelente! As cerdas são macias e o cabo não mofa se secar direitinho.');


-- ==========================================
-- INSERÇÃO DE FAVORITOS
-- ==========================================

INSERT INTO favoritos
(id_usuario, id_produto)
VALUES
(2, 4);


-- ==========================================
-- INSERÇÃO DE CONTATOS
-- ==========================================

INSERT INTO contato
(id_usuario, mensagem)
VALUES
(2, 'Olá! Vocês aceitam embalagens de volta para logística reversa e reciclagem?');


-- ==========================================
-- CONSULTAS DE TESTE
-- ==========================================

SELECT * FROM usuario;
SELECT * FROM endereco;
SELECT * FROM produtos;
SELECT * FROM carrinho;
SELECT * FROM pedidos;
SELECT * FROM pedido_itens;
SELECT * FROM pagamentos;
SELECT * FROM avaliacoes;
SELECT * FROM favoritos;
SELECT * FROM contato;


-- ==========================================
-- ATUALIZAR TIPO DE USUÁRIO
-- ==========================================

UPDATE usuario
SET tipo = 'admin'
WHERE email = 'contato@aruanaeco.com.br';

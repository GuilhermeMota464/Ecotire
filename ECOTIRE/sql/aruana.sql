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

-- ================= 1. INSERTS: USUÁRIO =================
-- Inserindo um admin e dois clientes
INSERT INTO usuario (nome, email, senha, telefone, tipo) VALUES
('Admin Sistema', 'admin@loja.com', '$2b$12$K8j7H6g5F4d3S2a1...', '11999999999', 'admin'),
('Carlos Silva', 'carlos@email.com', '$2b$12$XyZ1v2w3u4t5s6r7...', '11988888888', 'cliente'),
('Ana Souza', 'ana@email.com', '$2b$12$AbC9876543210zyx...', '21977777777', 'cliente');

-- ================= 2. INSERTS: ENDEREÇO =================
-- IDs gerados automaticamente para usuários: Carlos (2) e Ana (3)
INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES
(2, '01310-100', '1000', 'Apto 42'),
(2, '04571-010', '150', 'Bloco B'),
(3, '22021-001', '55', 'Casa 2');

-- ================= 3. INSERTS: PRODUTOS =================
-- Cadastrando alguns produtos eletrônicos/variados
INSERT INTO produtos (nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, ativo) VALUES
('Smartphone Galaxy S24', 3500.00, 4999.90, 4599.00, 'Samsung Ultra', 15, TRUE),
('Notebook Nitro 5', 2800.00, 3999.00, NULL, 'Acer Core i5', 8, TRUE),
('Fone de Ouvido Bluetooth', 80.00, 199.90, 149.90, 'JBL Wave Flex', 50, TRUE),
('Teclado Mecânico RGB', 120.00, 299.90, NULL, 'HyperX Alloy', 0, TRUE); -- Produto esgotado

-- ================= 4. INSERTS: CARRINHO =================
-- Carlos (2) adicionou produtos ao carrinho
INSERT INTO carrinho (id_usuario, id_produto, quantidade) VALUES
(2, 1, 1), -- 1x Smartphone
(2, 3, 2); -- 2x Fones

-- ================= 5. INSERTS: PEDIDOS =================
-- Carlos (2) comprou enviando para seu endereço principal (1)
INSERT INTO pedidos (id_usuario, id_endereco_entrega, status, total) VALUES
(2, 1, 'pago', 4898.80); -- (1x 4599.00) + (2x 149.90)

-- ================= 6. INSERTS: ITENS DO PEDIDO =================
-- Detalhes do pedido acima (id_pedido = 1)
INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES
(1, 1, 1, 4599.00),
(1, 3, 2, 149.90);

-- ================= 7. INSERTS: PAGAMENTOS =================
-- Pagamento aprovado via PIX para o pedido 1
INSERT INTO pagamentos (id_pedido, metodo, valor, status, codigo_transacao, data_pagamento) VALUES
(1, 'PIX', 4898.80, 'aprovado', 'PIX123456789ABCDEF', NOW());

-- ================= 8. INSERTS: AVALIAÇÕES =================
-- Carlos (2) avaliou o Smartphone (1)
INSERT INTO avaliacoes (id_usuario, id_produto, nota, comentario) VALUES
(2, 1, 5, 'Celular incrível, câmera impecável e bateria dura muito!');

-- ================= 9. INSERTS: FAVORITOS =================
-- Ana (3) favoritou o Notebook (2) e o Fone (3)
INSERT INTO favoritos (id_usuario, id_produto) VALUES
(3, 2),
(3, 3);

-- ================= 10. INSERTS: CONTATO / MENSAGENS =================
-- Ana (3) enviando uma dúvida ao suporte
INSERT INTO contato (id_usuario, mensagem) VALUES
(3, 'Gostaria de saber quando o notebook Nitro 5 terá desconto em boleto.');

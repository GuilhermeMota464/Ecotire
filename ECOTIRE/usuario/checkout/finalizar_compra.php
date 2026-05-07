<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = 'Sessão expirada. Faça login novamente.';
    header("Location: ../login/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verifica se é para adicionar um novo endereço (via form simples)
if (isset($_POST['cep']) && isset($_POST['numero'])) {
    $cep = isset($_POST['cep']) ? trim($_POST['cep']) : '';
    $numero = isset($_POST['numero']) ? intval($_POST['numero']) : 0;
    $complemento = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';

    if (empty($cep) || $numero <= 0) {
        $_SESSION['erro'] = 'CEP e número são obrigatórios.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

// Limpa CEP (remove caracteres nao numericos)
    $cep_limpo = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep_limpo) != 8) {
        $_SESSION['erro'] = 'CEP inválido. Deve ter 8 dígitos.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Formata CEP: 00000-000
    $cep_formatado = substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3);

    try {
        // Insere o endereço
        $sql = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario, $cep_formatado, $numero, $complemento]);
        
        $_SESSION['sucesso'] = 'Endereço adicionado com sucesso! Agora finalize sua compra.';
        header("Location: ../carrinho/carrinho.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao adicionar endereço: ' . $e->getMessage();
        header("Location: ../carrinho/carrinho.php");
        exit;
    }
}

// Caso contrário, é para processar a compra (recebe dados via POST formato regular)
$id_endereco = isset($_POST['id_endereco']) ? intval($_POST['id_endereco']) : 0;
$metodo_pagamento = isset($_POST['metodo_pagamento']) ? $_POST['metodo_pagamento'] : 'pix';

if ($id_endereco <= 0) {
    $_SESSION['erro'] = 'Selecione um endereço de entrega.';
    header("Location: ../carrinho/carrinho.php");
    exit;
}

try {
    // Verifica se o carrinho tem itens
    $sql_check = "SELECT c.*, p.nome, p.preco as preco_produto
                 FROM carrinho c
                 JOIN produtos p ON c.id_produto = p.id_produto
                 WHERE c.id_usuario = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$id_usuario]);
    $itens_carrinho = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

    if (empty($itens_carrinho)) {
        $_SESSION['erro'] = 'Carrinho vazio.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Verifica se o endereço pertence ao usuário
    $sql_endereco = "SELECT id_endereco FROM endereco WHERE id_endereco = ? AND id_usuario = ?";
    $stmt_endereco = $pdo->prepare($sql_endereco);
    $stmt_endereco->execute([$id_endereco, $id_usuario]);
    if (!$stmt_endereco->fetch()) {
        $_SESSION['erro'] = 'Endereço inválido.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Inicia transação
    $pdo->beginTransaction();

    // Para cada item do carrinho, cria um pedido
    $total_geral = 0;
    $itens_inseridos = [];

    foreach ($itens_carrinho as $item) {
        $subtotal = $item['preco_unitario'] * $item['quantidade'];
        $total_geral += $subtotal;

        $sql_pedido = "INSERT INTO pedidos 
                      (id_usuario, id_produto, id_endereco_entrega, quantidade, total, preco_unitario, metodo_pagamento, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, 'pendente')";
        
        $stmt_pedido = $pdo->prepare($sql_pedido);
        $stmt_pedido->execute([
            $id_usuario,
            $item['id_produto'],
            $id_endereco,
            $item['quantidade'],
            $subtotal,
            $item['preco_unitario'],
            $metodo_pagamento
        ]);

        $itens_inseridos[] = $pdo->lastInsertId();
    }

    // Limpa o carrinho do usuário
    $sql_limpar = "DELETE FROM carrinho WHERE id_usuario = ?";
    $stmt_limpar = $pdo->prepare($sql_limpar);
    $stmt_limpar->execute([$id_usuario]);

    // Confirma a transação
    $pdo->commit();

    // Redireciona para página de sucesso
    $_SESSION['sucesso'] = 'Pedido realizado com sucesso!';
    $_SESSION['ultimo_pedido'] = $itens_inseridos[0];
    $_SESSION['total_pedido'] = $total_geral;
    
    // Redireciona para página de pedido realizado
    header("Location: pedido_sucesso.php");

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['erro'] = 'Erro ao processar pedido: ' . $e->getMessage();
    header("Location: ../carrinho/carrinho.php");
    exit;
}

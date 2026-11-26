<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include 'connection.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'logado' => false]);
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

if (!$id_produto || !$quantidade || $quantidade < 1) {
    echo json_encode(['ok' => false, 'mensagem' => 'Quantidade inválida.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id_produto = ? AND ativo = 1");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo json_encode(['ok' => false, 'mensagem' => 'Produto não encontrado.']);
        exit;
    }

    $estoque = (int) $produto['estoque'];
    if ($estoque <= 0) {
        echo json_encode(['ok' => false, 'mensagem' => 'Produto esgotado.']);
        exit;
    }

    $stmtCart = $pdo->prepare("SELECT id_item, quantidade FROM carrinho WHERE id_usuario = ? AND id_produto = ?");
    $stmtCart->execute([$id_usuario, $id_produto]);
    $item = $stmtCart->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $novaQtd = min((int) $item['quantidade'] + $quantidade, $estoque);
        $stmtUpd = $pdo->prepare("UPDATE carrinho SET quantidade = ? WHERE id_item = ?");
        $stmtUpd->execute([$novaQtd, $item['id_item']]);
    } else {
        $novaQtd = min($quantidade, $estoque);
        $stmtIns = $pdo->prepare("INSERT INTO carrinho (id_usuario, id_produto, quantidade) VALUES (?, ?, ?)");
        $stmtIns->execute([$id_usuario, $id_produto, $novaQtd]);
    }

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'mensagem' => 'Erro ao adicionar produto ao carrinho.']);
}
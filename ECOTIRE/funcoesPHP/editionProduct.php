<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Método de requisição inválido.'
    ]);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
if ($modelo === '') {
    $modelo = $nome;
}

$preco_venda = floatval($_POST['preco_venda'] ?? 0);
$promocao = isset($_POST['promo']) && intval($_POST['promo']) === 1;
$preco_promocional_raw = $_POST['preco_promocional'] ?? '';
$preco_promocional = ($promocao && floatval($preco_promocional_raw) > 0)
    ? floatval($preco_promocional_raw)
    : null;

$descricao = trim($_POST['descricao'] ?? '');
$estoque = max(0, intval($_POST['estoque'] ?? 0));

if ($id <= 0) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'ID do produto inválido.'
    ]);
    exit;
}

if ($nome === '' || $preco_venda <= 0) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Preencha o nome e um preço de venda maior que zero.'
    ]);
    exit;
}

if ($promocao && ($preco_promocional === null || $preco_promocional >= $preco_venda)) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'O preço promocional deve ser menor que o preço de venda.'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE produtos
        SET nome = :nome,
            preco_venda = :preco_venda,
            preco_promocional = :preco_promocional,
            modelo = :modelo,
            descricao = :descricao,
            estoque = :estoque
        WHERE id_produto = :id
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':preco_venda' => $preco_venda,
        ':preco_promocional' => $preco_promocional,
        ':modelo' => $modelo,
        ':descricao' => $descricao,
        ':estoque' => $estoque,
        ':id' => $id
    ]);

    echo json_encode([
        'ok' => true,
        'mensagem' => 'Produto atualizado com sucesso!'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Erro ao atualizar o produto no banco de dados.'
    ]);
}
<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/connection.php';

$id = intval($_GET['delete_id'] ?? $_POST['delete_id'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'ID do produto inválido.'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = ?");
    $stmt->execute([$id]);

    echo json_encode([
        'ok' => true,
        'mensagem' => 'Produto excluído com sucesso.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Erro ao excluir o produto: ' . $e->getMessage()
    ]);
}
<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $valor = floatval($_POST['preco']);
    $promocao = intval($_POST['promo']);
    $descricao = trim($_POST['descricao']);
    $estoque = intval($_POST['estoque']);

    if ($id > 0) {

        $stmt = $conn->prepare("
            UPDATE produtos 
            SET nome = ?, preco = ?, descricao = ?, promo = ?, estoque = ?
            WHERE id_produto = ?
        ");

        if (!$stmt) {
            die("Erro na query: " . $conn->error);
        }

        $stmt->bind_param("sdsiii", $nome, $valor, $descricao, $promocao, $estoque, $id);

        if ($stmt->execute()) {
            echo "Produto atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID inválido.";
    }
}
?>
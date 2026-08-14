<?php
include 'connection.php';

if (!isset($pdo)) {
    die("Sem conexão com o banco");
}

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        die("Erro ao deletar produto: " . $e->getMessage());
    }

    header("Location: ../Admin/produtos/produtos-admin.php");
    exit;
}
?>
<?php
session_start();
include 'connection.php';
if (isset($_GET['id_item']) && is_numeric($_GET['id_item'])) {
    $id = $_GET['id_item'];

    $sql = "DELETE FROM carrinho WHERE id_item = :id";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([':id' => $id]);

        header("Location: ../usuario/carrinho/carrinho.php"); 
        exit;
    } catch (PDOException $e) {
        die("Erro ao remover: " . $e->getMessage());
    }
} else {
    die("ID de item inválido ou não fornecido.");
}
?>
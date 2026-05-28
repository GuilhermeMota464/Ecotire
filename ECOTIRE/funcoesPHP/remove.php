<?php
include 'connection.php';
if (!isset($pdo)) {
    die("Sem conexão com o banco");
}
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = :id");
    $stmt->execute([':id' => $id]);
    header("location: ../Admin/produtos/produtos-admin.php");
}
?>
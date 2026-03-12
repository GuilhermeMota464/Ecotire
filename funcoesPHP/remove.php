<?php
include 'connection.php';

if (isset($_GET['deletar_id'])) {
    $id_para_del = $_GET['deletar_id'];
    
$stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");

$stmt->bind_param("i", $id_para_deletar);

$stmt->close();
}
?>
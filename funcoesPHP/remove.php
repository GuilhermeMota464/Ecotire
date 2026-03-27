<?php
include 'connection.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

$stmt = $conn->prepare("DELETE FROM produtos WHERE id_produto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
}
?>
<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    echo json_encode(['logado' => true]);
} else {
    echo json_encode(['logado' => false]);
}
?>
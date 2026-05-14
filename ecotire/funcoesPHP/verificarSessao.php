<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    echo json_encode(['logado' => true]);
} else {
    echo json_encode(['logado' => false]);
}
?>
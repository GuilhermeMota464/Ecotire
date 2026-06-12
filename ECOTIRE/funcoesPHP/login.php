<?php
session_start(); 
include 'connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $senhaDigitada = $_POST['senha'] ?? '';

    try {
        $sql = "SELECT id_usuario, nome, email, senha FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
    
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senhaDigitada, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'E-mail ou senha incorretos!']);
            exit;
        }
        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no sistema: ' . $e->getMessage()]);
        exit;
    }
}
?>
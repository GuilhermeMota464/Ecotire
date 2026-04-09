<?php
session_start(); //para realmente manter o login entre páginas
require 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $dominio = $_POST['domain'];
    $full_email = $email . $dominio;
    $senhaDigitada = $_POST['senha'];

    try {
        $sql = "SELECT id_usuario, nome, email, senha FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $full_email]);
    
        $usuario = $stmt->fetch();

        // password_verify() compara a senha digitada com o hash salvo no banco
        if ($usuario && password_verify($senhaDigitada, $usuario['senha'])) {
            
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            header("Location: ../../usuario/inicio/index.php");
            exit; // Sempre use exit após um header de redirecionamento
            
        } else {
            // Falha! Usuário não encontrado ou senha incorreta
            // É boa prática dar uma mensagem genérica para não dar dicas a invasores
            echo "E-mail ou senha incorretos!";
        }
        
    } catch (PDOException $e) {
        echo "Erro no sistema: " . $e->getMessage();
    }
}
?>
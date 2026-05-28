<?php
include 'connection.php';
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email      = $_POST['email'];
    $dominio    = $_POST['domain'];
    $full_email = $email . $dominio;
    $senha      = $_POST['senha'];
    $telefone   = $_POST['telefone'];

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario (email, senha, telefone) VALUES (:email, :senha, :telefone)";
        
        $stmt = $connection->prepare($sql);

        $stmt->execute([
            ':email'    => $full_email,
            ':senha'    => $senha_hash,
            ':telefone' => $telefone
        ]);
        echo "Cadastro realizado com sucesso!";

    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>
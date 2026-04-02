<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fixed: POST values use square brackets [], not parentheses ()
    $email = trim($_POST['email']);
    $dominio  = trim($_POST['domain']);
    $full_email = trim($email . $dominio);
    $senha    = trim($_POST['senha']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    // Securely hash the password
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (email, senha, telefone) 
            VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $stmt -> execute([
        ':email' => $full_email,
        ':senha' => $senha_hash,
        ':email' => $telefone
    ]);

    if ($stmt->execute()) {
    } else {
        echo "Erro ao cadastrar: " . $connection->error;
    }

    $stmt->close();
}
?>
<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fixed: POST values use square brackets [], not parentheses ()
    $email    = $_POST['email'];
    $dominio  = $_POST['domain'];
    $full_email = $email . $dominio;
    $senha    = $_POST['senha'];
    $telefone = $_POST['telefone'];

    // Securely hash the password
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 1. Use '?' instead of named placeholders
    $sql = "INSERT INTO usuario (email, senha, telefone) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);

    // 2. Bind parameters: "sss" means 3 strings
    // Note: I used $full_email and $senha_hash here to ensure security/logic
    $stmt->bind_param("sss", $full_email, $senha_hash, $telefone);

    // 3. Execute
    if ($stmt->execute()) {
    } else {
        echo "Erro ao cadastrar: " . $connection->error;
    }

    $stmt->close();
}
?>
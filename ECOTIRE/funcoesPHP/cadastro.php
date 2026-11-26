<?php
require 'connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome       = trim($_POST['nome'] ?? '');
    $email_part = trim($_POST['email'] ?? '');
    $dominio    = $_POST['domain'] ?? ''; 
    $telefone   = trim($_POST['telefone'] ?? '');
    $senha      = $_POST['senha'] ?? '';

    $full_email = ($dominio === 'outro') ? $email_part : $email_part . $dominio;
    $digitos_telefone = preg_replace('/\D/', '', $telefone);

    if (empty($nome) || empty($email_part) || empty($senha)) {
        $_SESSION['feedback'] = "<div class='error'>Preencha todos os campos obrigatórios.</div>";
    } elseif (!filter_var($full_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['feedback'] = "<div class='error'>Formato de e-mail inválido.</div>";
    } elseif ($telefone !== '' && (strlen($digitos_telefone) < 10 || strlen($digitos_telefone) > 11)) {
        $_SESSION['feedback'] = "<div class='error'>Telefone inválido.</div>";
    } else {
        try {
            $checkSql = "SELECT id_usuario FROM usuario WHERE email = :email";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([':email' => $full_email]);

            if ($checkStmt->fetch()) {
                $_SESSION['feedback'] = "<div class='error'>O email '$full_email' já está registrado. Insira outro.</div>";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $telefone_db = ($telefone !== '') ? $telefone : null;

                $insertSql = "INSERT INTO usuario (nome, email, senha, telefone) VALUES (:nome, :email, :senha, :telefone)";
                $stmt = $pdo->prepare($insertSql);

                $sucesso = $stmt->execute([
                    ':nome'     => $nome,
                    ':email'    => $full_email,
                    ':senha'    => $senha_hash,
                    ':telefone' => $telefone_db
                ]);

                if ($sucesso) {
                    $_SESSION['feedback'] = "<div class='error' style='color: #2c9b2a;'>Cadastro realizado com sucesso!</div>";
                } else {
                    $_SESSION['feedback'] = "<div class='error'>Erro ao cadastrar seu perfil.</div>";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['feedback'] = "<div class='error'>Erro no sistema: " . $e->getMessage() . "</div>";
        }
    }

    header("Location: ../usuario/cadastro/cadastro.php");
    exit();
}
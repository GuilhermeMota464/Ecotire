<?php
require '../../funcoesPHP/connection.php';

$feedback = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome       = $_POST['nome'];
    $email_part = $_POST['email'];
    $dominio    = $_POST['domain']; 
    $telefone   = $_POST['telefone'];
    $senha      = $_POST['senha'];

    $full_email = $email_part . $dominio;

    try {
        $checkSql = "SELECT id_usuario FROM usuario WHERE email = :email";
        $checkStmt = $pdo->prepare($checkSql);
        
        $checkStmt->execute([':email' => $full_email]);

        if ($checkStmt->fetch()) {
            $feedback = "<p style='color: #dc3545; text-align: center;'>O email '$full_email' já está registrado. Insira outro.</p>";

        } else {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $insertSql = "INSERT INTO usuario (nome, email, senha, telefone) VALUES (:nome, :email, :senha, :telefone)";
            $stmt = $pdo->prepare($insertSql);
            
            $sucesso = $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $full_email,
                ':senha'    => $senha_hash,
                ':telefone' => $telefone
            ]);

            if ($sucesso) {
                $feedback = "<p style='color: #28a745; text-align: center;'>Cadastro realizado com sucesso!</p>";
            } else {
                $feedback = "<p style='color: #dc3545; text-align: center;'>Erro ao cadastrar.</p>";
            }
        }

    } catch (PDOException $e) {
        $feedback = "<p style='color: #dc3545; text-align: center;'>Erro no sistema: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    
    <title>Ecotire</title>
</head>
<body>

    <form action="cadastro.php" method="post" class="card" id="cadastroForm">
        <div class="logo">
            <img src="../../assetsGerais/ecotire.webp" alt="Logo Ecotire" onclick="window.location.href='../inicio/index.php'">
        </div>

        <h2>Crie sua conta na Ecotire</h2>
        
        <?php echo $feedback; ?>

        <p>Insira seu endereço de email.</p>

        <div class="row">
            <input name="email" id="email" type="text" placeholder="Email" required>
            <select id="domain" name="domain">
                <option value="@gmail.com">@gmail.com</option>
                <option value="@hotmail.com">@hotmail.com</option>
                <option value="@yahoo.com">@yahoo.com</option>
                <option value="@outlook.com">@outlook.com</option>
                <option value="@icloud.com">@icloud.com</option>
                <option value="outro">Outro...</option>
            </select>
        </div>

        <div class="row">
            <input name="senha" type="password" id="senha" placeholder="Crie uma senha" required>
        </div>

        <div class="row">
            <input name="nome" type="text" id="nome" placeholder="Insira seu nome" required>
        </div>

        <div class="row">
            <input name="telefone" type="tel" id="tele" placeholder="Telefone" required>
        </div>

    <input type="checkbox" id="terms" required>
    <label for="terms">Eu aceito os <span id="termos" onclick="window.location.href='../termos/termosdeuso.php'">termos e condições</span></label>

        <button type="submit">Cadastrar</button>

        <a href="../login/login.php">Entrar</a>
    </form>

    <script src="script.js"></script>
</body>
</html>
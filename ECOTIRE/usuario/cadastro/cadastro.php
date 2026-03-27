<?php
include '../../funcoesPHP/connection.php';

$feedback = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Get the data
    $email_part = $_POST['email'];
    $dominio    = $_POST['domain']; 
    $telefone   = $_POST['telefone'];
    $senha      = $_POST['senha'];

    // FIX: Handle the "Outro" case or concatenate correctly
    // If the user selects "Outro", this logic might break unless you have javascript changing the input.
    // For now, we assume standard concatenation:
    $full_email = $email_part . $dominio;

    // 2. CHECK FIRST (Look Before You Leap)
    // FIX: Use $connection (not $mysqli) and check the $full_email
    $checkStmt = $connection->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
    $checkStmt->bind_param("s", $full_email); // Check the FULL email, not just the name
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // --- STOP! Email found ---
        // We store the error in $feedback to display it nicely in the HTML later
        $feedback = "<p style='color: #dc3545; text-align: center;'>O email '$full_email' já está registrado. Insira outro.</p>";
        
        $checkStmt->close(); 
        // We do NOT proceed to insert. The code ends here for this request.

    } else {
        // --- PROCEED! Email is new ---
        $checkStmt->close(); // Close the check statement

        // 3. Hash the password (only do this if we are actually saving)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // 4. Insert into Database
        $sql = "INSERT INTO usuario (email, senha, telefone) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        
        // FIX: Added error checking for the prepare statement itself
        if ($stmt) {
            $stmt->bind_param("sss", $full_email, $senha_hash, $telefone);

            if ($stmt->execute()) {
                $feedback = "<p style='color: #28a745; text-align: center;'>Cadastro realizado com sucesso!</p>";
                // Optional: Redirect to login after a few seconds?
                // header("refresh:3;url=../login/login.php"); 
            } else {
                $feedback = "<p style='color: #dc3545; text-align: center;'>Erro ao cadastrar: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            $feedback = "<p style='color: #dc3545; text-align: center;'>Erro no sistema (Prepare failed).</p>";
        }
    }
    
    // connection close is usually handled at the end of the script or automatically
    // $connection->close(); 
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
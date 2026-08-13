<?php
require '../../funcoesPHP/connection.php';

$feedback = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome       = $_POST['nome'];
    $email_part = $_POST['email'];
    $dominio    = $_POST['domain']; 
    $telefone   = $_POST['telefone'];
    $senha      = $_POST['senha'];

    $full_email = ($dominio === 'outro') ? $email_part : $email_part . $dominio;

    try {
        $checkSql = "SELECT id_usuario FROM usuario WHERE email = :email";
        $checkStmt = $pdo->prepare($checkSql);
        
        $checkStmt->execute([':email' => $full_email]);

        if ($checkStmt->fetch()) {
            $feedback = "<div class='error'>O email '$full_email' já está registrado. Insira outro.</div>";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $insertSql = "INSERT INTO usuario (nome, email, senha, telefone, genero) VALUES (:nome, :email, :senha, :telefone, :genero)";
            $stmt = $pdo->prepare($insertSql);

            
            $genero = $_POST['genero'] ?? 'prefiro_nao_dizer';

            $sucesso = $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $full_email,
                ':senha'    => $senha_hash,
                ':telefone' => $telefone,
                ':genero'   => $genero
            ]);

            if ($sucesso) {
                $feedback = "<div class='error' style='color: #2c9b2a;'>Cadastro realizado com sucesso!</div>";
            } else {
                $feedback = "<div class='error'>Erro ao cadastrar seu perfil.</div>";
            }

        }

    } catch (PDOException $e) {
        $feedback = "<div class='error'>Erro no sistema: " . $e->getMessage() . "</div>";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title>Aruanã - Cadastro</title>
</head>
<body>

    <main class="main-wrapper">
        <section class="side-panel">
            <a href="../inicio/index.php"><i class="fa-solid fa-arrow-left" id="seta"></i></a>
            
            <div class="side-content">
                <div class="logo-wrapper">
                    <img src="../../assetsGerais/aruana.webp" alt="Aruanã logo" class="logo-img">
                </div>
                <h1>Crie sua <br><span>Conta</span></h1>
                <p>Junte-se à Aruanã e faça parte de uma jornada tecnológica voltada para um futuro mais sustentável.</p>
            </div>
            
            <div class="creator-footer">
                <span>ARUANÃ | SUSTENTABILIDADE</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-container">
                <h2>Cadastre-se</h2>
                
                <?php echo $feedback; ?>

                <form action="cadastro.php" method="post" id="cadastroForm">
                    
                    <div class="input-group">
                        <label for="nome">Nome</label>
                        <input name="nome" type="text" id="nome" placeholder="Insira seu nome" required>
                    </div>

                    <div class="row">
                        <div class="input-group">
                            <label for="email">E-mail</label>
                            <input name="email" id="email" type="text" placeholder="Nome do usuário" required>
                        </div>
                        <div class="input-group">
                            <label for="domain">Domínio</label>
                            <select id="domain" name="domain">
                                <option value="@gmail.com">@gmail.com</option>
                                <option value="@hotmail.com">@hotmail.com</option>
                                <option value="@yahoo.com">@yahoo.com</option>
                                <option value="@outlook.com">@outlook.com</option>
                                <option value="@icloud.com">@icloud.com</option>
                                <option value="outro">Outro...</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="tele">Telefone</label>
                        <input name="telefone" type="tel" id="tele" placeholder="(00) 00000-0000" required>
                    </div>

                    <div class="input-group">
                        <label for="senha">Senha</label>
                        <input name="senha" type="password" id="senha" placeholder="Crie uma senha forte" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">
                            Eu li e concordo com os&nbsp;<span id="termos" onclick="window.location.href='../termos/termosdeuso.php'">Termos e condições</span>
                        </label>
                    </div>

                    <div class="row">
                        <div class="input-group">
                            <label for="genero">Gênero</label>
                            <select id="genero" name="genero" required>
                                <option value="masculino">Masculino</option>
                                <option value="feminino">Feminino</option>
                                <option value="outros">Outros</option>
                                <option value="prefiro_nao_dizer">Prefiro não dizer</option>
                            </select>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">Cadastrar</button>
                        <a href="../login/login.php" class="btn-outline">Entrar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>


<?php
session_start();
$feedback = $_SESSION['feedback'] ?? '';
unset($_SESSION['feedback']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <title>Aruanã - Cadastro</title>
</head>
<body>
    <main class="main-wrapper">
        <section class="form-panel">
            <a href="../inicio/index.php" class="voltar" aria-label="Voltar para o início">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="form-container">
                <h1>Crie sua conta</h1>
                <p class="subtitle">Faça parte da jornada sustentável da Aruanã.</p>

                <div id="error" class="error"></div>
                <?php echo $feedback; ?>

                <form action="../../funcoesPHP/cadastro.php" method="post" id="cadastroForm">
                    <div class="input-group">
                        <label for="nome">Nome</label>
                        <div class="campo">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input name="nome" type="text" id="nome" placeholder="Insira seu nome" autocomplete="name" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <div class="email-row">
                            <div class="campo email-user">
                                <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                                <input name="email" id="email" type="text" placeholder="Seu usuário" required>
                            </div>
                            <select id="domain" name="domain" aria-label="Domínio do e-mail">
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
                        <div class="campo">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            <input name="telefone" type="tel" id="tele" placeholder="(00) 00000-0000" autocomplete="tel" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="senha">Senha</label>
                        <div class="campo">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input name="senha" type="password" id="senha" placeholder="Crie uma senha forte" autocomplete="new-password" required>
                            <button type="button" class="olho" id="toggleSenha" aria-label="Mostrar senha">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Eu li e concordo com os</label>
                        <a href="../termos/termosdeuso.php" target="_blank" rel="noopener">Termos e Condições</a>
                    </div>

                    <button type="submit" class="btn-submit">Cadastrar <i class="fa-solid fa-arrow-right"></i></button>
                </form>

                <div class="divider"><span>ou</span></div>
                <div class="social-buttons">
                    <div id="g_id_onload"
                        data-client_id="1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com"
                        data-callback="handleGoogleLogin"></div>
                    <div class="g_id_signin" data-type="standard" data-theme="outline" data-size="large" data-width="300"></div>
                </div>
                <p class="switch-auth">Já tem uma conta? <a href="../login/login.php">Entrar</a></p>
            </div>
        </section>

        <section class="image-panel">
            <img src="../../assetsGerais/imagemLogin.png" alt="" class="side-image">
            <div class="image-overlay">
                <img src="../../assetsGerais/aruanaCabecario.webp" alt="Logo Aruanã" class="overlay-logo">
                <p class="overlay-texto">O futuro começa nas escolhas de hoje.</p>
            </div>
        </section>
    </main>

    <script>
    (function () {
        const button = document.getElementById('toggleSenha');
        const field = document.getElementById('senha');
        if (!button || !field) return;
        button.addEventListener('click', function () {
            const show = field.type === 'password';
            field.type = show ? 'text' : 'password';
            button.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
            button.querySelector('i').className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    })();
    </script>
    
</body>
</html>

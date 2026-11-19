<?php
session_start();

$jaLogado     = isset($_SESSION['id_usuario']);
$primeiroNome = explode(' ', trim($_SESSION['usuario_nome'] ?? ''))[0];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecotire - Login</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!$jaLogado): ?>
    <!-- Script do Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php endif; ?>
</head>
<body>
    <div class="main-wrapper">

        <section class="form-panel">
            <div class="seta-wrapper">
                <a href="../inicio/index.php" aria-label="Voltar ao início"><i class="fa-solid fa-arrow-left" id="seta"></i></a>
            </div>

            <div class="form-container">

            <?php if ($jaLogado): ?>
                <!-- Usuário que já está logado -->
                <div class="logged-card">
                    <div class="logged-avatar"><i class="fa-solid fa-circle-check"></i></div>
                    <h2>Você já está conectado</h2>
                    <p class="subtitle">Conta ativa como <strong><?= htmlspecialchars($primeiroNome) ?></strong>.</p>

                    <a href="../inicio/index.php" class="btn-submit btn-link">Ir para o início</a>
                    <a href="../../funcoesPHP/logout.php" class="btn-secondary">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair e trocar de conta
                    </a>
                </div>

            <?php else: ?>
                <h2>Entre na sua conta</h2>
                <p class="subtitle">Bem-vindo de volta à Aruanã.</p>

                <form id="loginForm" method="POST" action="../../funcoesPHP/login.php">
                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Senha</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="senha" placeholder="Digite sua senha" required>
                            <button type="button" class="toggle-senha" id="toggle-senha" aria-label="Mostrar senha">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Eu li e concordo com os<span>&nbsp;Termos e Condições</span></label>
                    </div>

                    <button type="submit" class="btn-submit">Entrar</button>
                </form>

                <div class="divider"><span>ou</span></div>

                <div class="social-buttons">
                    <div id="g_id_onload"
                        data-client_id="1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com"
                        data-callback="handleGoogleLogin">
                    </div>
                    <div class="g_id_signin" data-type="standard" data-theme="outline" data-size="large" data-width="300"></div>
                </div>

                <p class="switch-auth">Não tem conta? <a href="../cadastro/cadastro.php">Cadastre-se</a></p>
            <?php endif; ?>

            </div>
        </section>

        <section class="image-panel">
            <img src="../../assetsGerais/imagemLogin.png" alt="Aruanã" class="side-image">
        </section>

    </div>

    <script src="script.js"></script>

<script>
function handleGoogleLogin(response) {
    Swal.fire({
        title: 'Verificando dados...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('../../funcoesPHP/loginGoogle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ credential: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Resposta do servidor:', data);

        if (data.success) {
            // Avisa a página inicial para mostrar a notificação de boas-vindas
            sessionStorage.setItem('loginSucesso', '1');

            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Login realizado com sucesso! Redirecionando...',
                showConfirmButton: false,
                timer: 1800
            }).then(() => {
                window.location.href = data.redirect || '../inicio/index.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro ao entrar',
                text: data.message || 'Não foi possível entrar com Google.',
                confirmButtonColor: '#2c9b2a'
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro de conexão',
            text: 'Falha de comunicação com o servidor.',
            confirmButtonColor: '#2c9b2a'
        });
    });
}
</script>
</body>
</html>
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

    <!-- Script do Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <div class="main-wrapper">

        <section class="form-panel">
            <div class="seta-wrapper">
                <a href="../inicio/index.php"><i class="fa-solid fa-arrow-left" id="seta"   ></i></a>
            </div>

            <div class="form-container">
                <h2>Entre na sua conta</h2>
                <p class="subtitle">Bem-vindo de volta à Aruanã.</p>

                <form id="loginForm" method="POST" action="../../funcoesPHP/login.php">
                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="senha" placeholder="Digite sua senha">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Eu li e concordo com os<span href="../termos/termosdeuso.php">&nbspTermos e Condições</span></label>
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
            </div>
        </section>

        <section class="image-panel">
            <img src="../../assetsGerais/imagemLogin.png" alt="Aruanã" class="side-image">
        </section>

    </div>

    <script src="script.js"></script>

<script>
function handleGoogleLogin(response) {
    fetch('../../funcoesPHP/loginGoogle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            credential: response.credential
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log('Resposta do servidor:', data);

        if (data.success) {

            window.location.href =
                data.redirect || '../inicio/index.php';

        } else {

            Swal.fire(
                'Erro',
                data.message || 'Não foi possível entrar com Google.',
                'error'
            );
        }
    })
    .catch(error => {

        console.error('Erro:', error);

        Swal.fire(
            'Erro',
            'Falha de comunicação com o servidor.',
            'error'
        );
    });
}
</script>
</body>
</html>
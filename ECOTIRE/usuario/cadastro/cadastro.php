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
    <title>Aruanã - Cadastro</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script do Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <main class="main-wrapper">

        <section class="form-panel">
            <div class="seta-wrapper">
                <a href="../inicio/index.php" aria-label="Voltar ao início"><i class="fa-solid fa-arrow-left" id="seta"></i></a>
            </div>

            <div class="form-container">
                <h2>Crie sua conta</h2>
                <p class="subtitle">Junte-se à Aruanã e faça parte de um futuro mais sustentável.</p>

                <div id="error" class="error" role="alert"></div>
                <?php echo $feedback; ?>

                <form action="../../funcoesPHP/cadastro.php" method="post" id="cadastroForm" novalidate>

                    <!-- Gênero não é mais escolhido pelo usuário; valor fixo para o cadastro.php continuar funcionando -->
                    <input type="hidden" name="genero" value="prefiro_nao_dizer">

                    <div class="input-group">
                        <label for="nome">Nome</label>
                        <input name="nome" type="text" id="nome" placeholder="Insira seu nome" autocomplete="name" required>
                    </div>

                    <div class="row">
                        <div class="input-group">
                            <label for="email">E-mail</label>
                            <input name="email" id="email" type="text" placeholder="Nome do usuário" autocomplete="off" required>
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
                        <input name="telefone" type="tel" id="tele" placeholder="(00) 00000-0000"
                               inputmode="tel" autocomplete="tel" required>
                    </div>

                    <div class="input-group">
                        <label for="senha">Senha</label>
                        <div class="password-wrapper">
                            <input name="senha" type="password" id="senha" placeholder="Crie uma senha forte"
                                   autocomplete="new-password" required>
                            <button type="button" class="toggle-senha" id="toggle-senha" aria-label="Mostrar senha">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">
                            Eu li e aceito os <span id="termos" onclick="window.location.href='../termos/termosdeuso.php'">&nbsp;termos e condições</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">Cadastrar</button>
                </form>

                <div class="divider"><span>ou</span></div>

                <div class="social-buttons">
                    <div id="g_id_onload"
                        data-client_id="1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com"
                        data-callback="handleGoogleLogin">
                    </div>
                    <div class="g_id_signin" data-type="standard" data-theme="outline" data-size="large" data-width="300"></div>
                </div>

                <p class="switch-auth">Já tem conta? <a href="../login/login.php">Entrar</a></p>
            </div>
        </section>

        <section class="image-panel">
            <img src="../../assetsGerais/imagemCadastro.webp" alt="Perereca verde sobre um galho de pessegueiro em flor" class="side-image">
        </section>

    </main>

    <script src="script.js"></script>

<script>
function handleGoogleLogin(response) {
    Swal.fire({
        title: 'Verificando dados...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('../../funcoesPHP/cadastroGoogle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ credential: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Resposta do servidor:', data);

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Cadastro realizado! Redirecionando...',
                showConfirmButton: false,
                timer: 1800
            }).then(() => {
                window.location.href = data.redirect || '../inicio/index.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
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
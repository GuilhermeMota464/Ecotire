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
</head>
<body>
    <div class="main-wrapper">
        <section class="side-panel">
            <div class="side-content">
                <div class="seta-wrapper">
                    <a href="../inicio/index.php"><i class="fa-solid fa-arrow-left" id="seta"></i></a>
                </div>
                <div class="logo-wrapper">
                    <img src="../../assetsGerais/aruana.webp" alt="Aruana logo" class="logo-img">
                </div>
                <h1>Bem-vindo à <br><span>Aruanã</span></h1>
                <p>Onde a tecnologia encontra a sustentabilidade. Junte-se a nós para uma jornada mais verde.</p>
            </div>
            <div class="creator-footer">
                <span>ARUANÃ | SUSTENTABILIDADE</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-container">
                <h2>Entre na sua conta</h2>
                
                <form id="loginForm" method="POST" action="../../funcoesPHP/login.php">
                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">Entrar</button>
                        <a href="../cadastro/cadastro.php" class="btn-outline">Cadastrar</a>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
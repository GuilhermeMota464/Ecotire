<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title>Ecotire</title>
</head>
<body>
        <section class="side-panel">
            <div class="side-content">
                <div class="seta-wrapper"><a href="../inicio/index.php"><i class="fa-solid fa-arrow-left" id="seta"></i></a></div>
                <div class="logo-wrapper">
                    <img src="../../assetsGerais/aruana.webp" alt="Aruana logo" class="logo-img">
            </div>
                <h1>Bem-vindo à <br><span>Aruanã</span></h1>
                <p>Onde a tecnologia encontra a sustentabilidade. Junte-se a nós para uma jornada mais verde.</p>
            </div>
            <div class="creator-footer">
                <span>ARUANA | SUSTENTABILIDADE</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-container">
                <h2>Entre na sua conta</h2>
                
                <form>
                    <div class="input-group">
                        <label for="name">Nome</label>
                        <input type="text" id="name" placeholder="Seu nome completo">
                    </div>

                    <div class="input-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" placeholder="Digite seu email">
                    </div>

                    <div class="input-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" placeholder="Digite sua senha">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms">
                        <label for="terms">Eu li e concordo com os<span>&nbspTermos e Condições</span></label>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">Entrar</button>
                        <a href="../cadastro/cadastro.php" class="btn-outline">Cadastrar</a>
                    </div>
                </form>
            </div>
        </section>
    </body>
</html>
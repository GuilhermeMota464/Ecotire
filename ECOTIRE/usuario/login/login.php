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
    <main class="container">
        <section class="login-card">
            <header class="card-header">
                <div class="logo">
                    <img src="../../assetsGerais/aruana.webp" alt="Aruana logo" class="logo">
                </div>
                <h2>Bem-vindo de volta</h2>
                <p>Entre para continuar comprando consciente.</p>
            </header>

            <form>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="voce@ecoemail.com">
                </div>

                <div class="input-group">
                    <div class="label-row">
                        <label for="password">Senha</label>
                        <a href="#" class="forgot-password">Esqueci a senha</a>
                    </div>
                    <input type="password" id="password" placeholder="********">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms">
                    <label for="terms">Eu li e concordo com os <span>Termos e Condições</span> da EcoVenda.</label>
                </div>

                <button type="submit" class="btn-submit">Entrar</button>
            </form>

            <footer class="card-footer">
                <p>Novo por aqui? <a href="#">Criar uma conta</a></p>
            </footer>
        </section>
    </main>

    <div class="area-ondas">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="var(--cor-primaria)" d="M0,224L120,213.3C240,203,480,181,720,186.7C960,192,1200,224,1320,240L1440,256L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
        
        <path fill="var(--cor-ondas)" fill-opacity="1" d="M0,256L120,266.7C240,277,480,299,720,288C960,277,1200,235,1320,213.3L1440,192L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
    </svg>
    </div>
</body>
</html> 
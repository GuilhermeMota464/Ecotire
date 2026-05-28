<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aruanã</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Cabeçalho -->
    <header>
        <div class="header">
            <div class="header-top">
                <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruanã"
                    onclick="window.location.href='../inicio/index.php'">
            <div class='search-actions'>
                <div class="search-group">
                    <input type="text" class="search-bar" id="busca" placeholder="Pesquisar soluções sustentáveis...">
                    <button type="button" id="btn-busca"><i class="fa-solid fa-magnifying-glass" id="lupa"></i></button>
                    <div id="resultado"></div>
                </div>

                <div class="header-actions">
                    <i onclick="window.location.href = '../perfil/perfil.php'" class="fa-solid fa-circle-user"
                        title="Minha Conta"></i>
                    <i onclick="window.location.href = '../carrinho/carrinho.php'" class="fa-solid fa-cart-shopping"
                        title="Meu Carrinho"></i>
                </div>
            </div>
            </div>
        <div class="header-bottom">
                <button id="icon" class="hamburger" aria-label="Abrir menu" onclick="toggleMenu()">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <nav>
                    <ul class="menu-horizontal" id="menu-links">
                        <li><a onclick="window.location.href='../inicio/index.php'" class="ativo">Início</a></li>
                        <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'">Sobre Nós</a></li>
                        <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
                        <li class="contato-btn"><a href="#fale_conosco">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="imgAlternada">
            <div class="Slide">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 1">
                <img src="../../assetsGerais/placeholder.webp" alt="Banner 2">
                <img src="../../assetsGerais/placeholder.webp" alt="Banner 3">
                <img src="../../assetsGerais/placeholder.webp" alt="Banner 4">
                <img src="../../assetsGerais/placeholder.webp" alt="Banner 5">
            </div>
            <button class="prev">❮</button>
            <button class="next">❯</button>
        </div>

        <section class="fale_conosco_container">
            <form class="fale_conosco_form">
                <div class="form_title">
                    <h2 id="fale_conosco">Fale Conosco</h2>
                    <p>Conecte-se com a natureza e tire suas dúvidas.</p>
                </div>

                <div class="form_groups">
                    <div class="input_group">
                        <div class="conjunto">
                            <label for="nome"><i class="fa-solid fa-user"></i></label>
                            <input type="text" id="nome" name="nome" required placeholder="Nome">
                        </div>
                        <div class="conjunto">
                            <label for="email"><i class="fa-solid fa-envelope"></i></label>
                            <input type="email" id="email" name="email" placeholder="E-mail">
                        </div>
                        <div class="conjunto">
                            <label for="telefone"><i class="fa-solid fa-phone"></i></label>
                            <input type="tel" id="telefone" name="telefone" required placeholder="Telefone">
                        </div>
                    </div>

                    <div class="mensagem_group">
                        <label for="mensagem" class="message-title"><i class="fa-solid fa-message"></i> Mensagem</label>
                        <textarea id="mensagem" name="mensagem" rows="5" required
                            placeholder="Inserir nome"></textarea>
                    </div>
                </div>
                <button id="enviar_mensagem" type="submit">Enviar Mensagem</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <h4>Informação</h4>
                <ul class="links">
                    <li><a href="../inicio/index.php">Início</a></li>
                    <li><a href="../sobre_nos/sobre_nos.html">Sobre nós</a></li>
                    <li><a href="../produto/produto.php">Produtos</a></li>
                    <li><a href="#">Contatos</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul class="links">
                    <li><a href="../termos/termosdeuso.php">Termos de uso</a></li>
                    <li><a href="#">Políticas</a></li>
                    <li><a href="#">Carreiras</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Aruanã</h4>
                <p>
                    Líder em soluções sustentáveis. Criamos caminhos para um futuro onde o consumo e a natureza caminham
                    juntos em harmonia.
                </p>
            </div>
            <div class="footer-col">
                <h4>Newsletter</h4>
                <p>Receba atualizações exclusivas.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Seu e-mail" required>
                    <button type="submit">OK</button>
                </form>
                <div class="icons">
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>
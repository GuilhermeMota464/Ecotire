<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aruanã</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Cabeçalho -->
    <header>
        <div class="header">
            <div class="header-top">
                <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruanã"
                    onclick="window.location.href='../inicio/index.php'">

                <div class="search-group">
                    <input type="text" class="search-bar" id="busca" placeholder="Pesquisar soluções sustentáveis..."
                        aria-label="Pesquisar" autocomplete="off">
                    <button type="button" id="btn-busca" aria-label="Pesquisar"><i class="fa-solid fa-magnifying-glass" id="lupa"></i></button>
                    <div id="resultado" aria-live="polite"></div>
                </div>

                <div class="header-actions">
                    <i onclick="window.location.href = '../perfil/perfil.php'" class="fa-solid fa-circle-user icone-perfil"
                        title="Minha Conta"></i>
                    <button id="icon" class="hamburger" aria-label="Abrir menu" aria-expanded="false"
                        aria-controls="menu-links" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <i onclick="window.location.href = '../carrinho/carrinho.php'" class="fa-solid fa-cart-shopping"
                        title="Meu Carrinho"></i>
                </div>
            </div>

            <div class="header-bottom">
                <nav>
                    <ul class="menu-horizontal" id="menu-links">
                        <li><a href="../inicio/index.php" class="ativo" aria-current="page">Início</a></li>
                        <li><a href="../sobre_nos/sobre_nos.php">Sobre Nós</a></li>
                        <li><a href="../produto/produto.php">Produtos</a></li>
                        <li class="contato-btn"><a href="#fale_conosco">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">

        <!-- Banner -->
        <div class="imgAlternada" aria-roledescription="carrossel" aria-label="Destaques">
            <div class="Slide">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 1">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 2">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 3">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 4">
                <img src="../../assetsGerais/banner1.webp" alt="Banner 5">
            </div>
            <button type="button" class="prev" aria-label="Banner anterior">❮</button>
            <button type="button" class="next" aria-label="Próximo banner">❯</button>
            <div class="slide-dots" id="slide-dots"></div>
        </div>

        <!-- Apresentação -->
        <section class="intro" aria-labelledby="titulo-intro">
            <div class="intro-grid">
                <h1 id="titulo-intro">O futuro começa nas escolhas de hoje.</h1>
                <div class="intro-texto">
                    <p>A Aruanã reúne soluções sustentáveis para você pesquisar, comparar e comprar com
                        tranquilidade, sem complicação.</p>
                    <div class="intro-acoes">
                        <a class="btn btn-primario" href="../produto/produto.php">Ver produtos</a>
                        <a class="btn btn-secundario" href="#fale_conosco">Falar com a equipe</a>
                    </div>
                </div>
            </div>

            <ul class="destaques">
                <li>
                    <span class="destaque-icone" aria-hidden="true"><i class="fa-solid fa-leaf"></i></span>
                    <div>
                        <h2>Soluções sustentáveis</h2>
                        <p>Produtos pensados para reduzir o impacto ambiental no dia a dia.</p>
                    </div>
                </li>
                <li>
                    <span class="destaque-icone" aria-hidden="true"><i class="fa-solid fa-cart-shopping"></i></span>
                    <div>
                        <h2>Compra simples</h2>
                        <p>Pesquise, escolha e adicione ao carrinho em poucos cliques.</p>
                    </div>
                </li>
                <li>
                    <span class="destaque-icone" aria-hidden="true"><i class="fa-solid fa-headset"></i></span>
                    <div>
                        <h2>Atendimento próximo</h2>
                        <p>Tire suas dúvidas direto com a nossa equipe pelo formulário abaixo.</p>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Fale conosco -->
        <section class="fale_conosco_container">
            <div class="contato-lateral">
                <div class="form_title">
                    <h2 id="fale_conosco">Fale Conosco</h2>
                </div>
                <p>Conecte-se com a natureza e tire suas dúvidas.</p>
                <i class="fa-solid fa-seedling folha" aria-hidden="true"></i>
            </div>

            <form class="fale_conosco_form" id="form-contato" data-endpoint="" novalidate>
                <div class="form_groups">
                    <div class="campo">
                        <label for="nome">Nome</label>
                        <div class="campo-input">
                            <i class="fa-solid fa-user" aria-hidden="true"></i>
                            <input type="text" id="nome" name="nome" required placeholder="Seu nome" autocomplete="name">
                        </div>
                    </div>

                    <div class="campo">
                        <label for="telefone">Telefone</label>
                        <div class="campo-input">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            <input type="tel" id="telefone" name="telefone" required placeholder="(00) 00000-0000"
                                inputmode="tel" autocomplete="tel" maxlength="15">
                        </div>
                    </div>

                    <div class="campo largo">
                        <label for="email">E-mail <span class="opcional">(opcional)</span></label>
                        <div class="campo-input">
                            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" placeholder="voce@exemplo.com" autocomplete="email">
                        </div>
                    </div>

                    <div class="campo largo">
                        <label for="mensagem">Mensagem</label>
                        <div class="campo-input">
                            <i class="fa-solid fa-message" aria-hidden="true"></i>
                            <textarea id="mensagem" name="mensagem" rows="5" required
                                placeholder="Como podemos ajudar?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-rodape">
                    <p id="form-status" role="status" aria-live="polite"></p>
                    <button id="enviar_mensagem" type="submit">Enviar Mensagem</button>
                </div>
            </form>
        </section>
    </main>

<footer class="footer">
    <div class="footer-container">

        <div class="footer-top">
            <div class="footer-left">
                <p class="footer-mensagem">
                    O futuro começa nas escolhas de hoje.<br>
                    A Aruanã torna esse caminho mais simples.
                </p>
            </div>

            <div class="footer-right">
                <nav>
                    <ul>
                        <li><a href='../inicio/index.php'>Início</a></li>
                        <li><a href='../sobre_nos/sobre_nos.php'>Sobre nós</a></li>
                        <li><a href="../produto/produto.php">Produtos</a></li>
                        <li><a href="#fale_conosco">Contato</a></li>
                        <li><a href='../perfil/perfil.php'>Perfil</a></li>
                    </ul>
                </nav>

                <div class="social">
                    <a href="https://instagram.com" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://facebook.com" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-logo">
                <img src="../../assetsGerais/aruanaCabecario.webp" alt="Logo Aruanã">
            </div>

            <div class="footer-legal">
                <p>©<?php echo date('Y') ?> todos direitos reservados Aruanã</p>

                <div class="footer-links">
                    <a href="../termos/termosdeuso.php">Termos de uso</a>
                </div>
            </div>
        </div>

    </div>
</footer>

    <script src="script.js"></script>
</body>

</html>
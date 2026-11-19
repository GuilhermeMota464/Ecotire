<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include '../../funcoesPHP/connection.php';

$logado       = isset($_SESSION['id_usuario']);
$nomeCompleto = $_SESSION['usuario_nome'] ?? '';
$primeiroNome = explode(' ', trim($nomeCompleto))[0];

$hora = (int) date('H');
if ($hora < 12)     $saudacao = 'Bom dia';
elseif ($hora < 18) $saudacao = 'Boa tarde';
else                $saudacao = 'Boa noite';

$stmt = $pdo->query("SELECT id_produto, nome, preco_venda, preco_promocional, estoque, imagem FROM produtos");
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Produtos - Aruanã</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <button type="button" id="btn-busca" aria-label="Pesquisar">
                        <i class="fa-solid fa-magnifying-glass" id="lupa" aria-hidden="true"></i>
                    </button>
                    <div id="resultado" aria-live="polite"></div>
                </div>

                <div class="header-actions">
                    <?php if ($logado): ?>
                        <div class="user-menu" id="user-menu">
                            <button type="button" class="user-greeting" id="user-toggle"
                                    aria-haspopup="true" aria-expanded="false" aria-controls="user-dropdown">
                                <i class="fa-solid fa-circle-user user-avatar" aria-hidden="true"></i>
                                <span class="user-greeting-text">
                                    <small><?= $saudacao ?>,</small>
                                    <strong><?= htmlspecialchars($primeiroNome) ?></strong>
                                </span>
                                <i class="fa-solid fa-chevron-down user-chevron" aria-hidden="true"></i>
                            </button>

                            <div class="user-dropdown" id="user-dropdown">
                                <a href="../perfil/perfil.php"><i class="fa-solid fa-user"></i> Meu perfil</a>
                                <a href="../../funcoesPHP/logout.php" class="sair"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../login/login.php" class="btn-entrar" aria-label="Entrar" title="Entrar">
                            <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>

                    <button type="button" id="icon" class="hamburger" aria-label="Abrir menu" aria-expanded="false"
                        aria-controls="menu-links" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                    </button>

                    <a href="../carrinho/carrinho.php" id="botaoCarrinho" class="icone-carrinho" aria-label="Meu Carrinho" title="Meu Carrinho">
                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="header-bottom">
                <nav>
                    <ul class="menu-horizontal" id="menu-links">
                        <li><a href="../inicio/index.php">Início</a></li>
                        <li><a href="../sobre_nos/sobre_nos.php">Sobre Nós</a></li>
                        <li><a href="../produto/produto.php" class="ativo" aria-current="page">Produtos</a></li>
                        <li class="contato-btn"><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <h1 class="main-title">Nossos Produtos Sustentáveis</h1>

        <section class="products-grid">
            <?php foreach ($produtos as $produto): ?>
            <article class="product-card"
                onclick="location.href='../pagina-produto-usuario/pagina-produto-usuario.php?id=<?= htmlspecialchars((string)$produto['id_produto']) ?>'">
                <div class="image-container">
                    <img src="../../assetsProdutos/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                </div>
                <div class="product-info">
                    <h2 class="product-name"><?= htmlspecialchars($produto['nome']) ?></h2>
                    <?php if (!empty($produto['preco_promocional']) && $produto['preco_promocional'] < $produto['preco_venda']): ?>
                        <p class="price-old">
                            De: R$ <?= number_format((float)$produto['preco_venda'], 2, ',', '.') ?>
                        </p>
                        <p class="price-current">
                            Por: R$ <?= number_format((float)$produto['preco_promocional'], 2, ',', '.') ?>
                        </p>
                        <p class="price-full">
                            ou 12x de R$ <?= number_format((float)$produto['preco_promocional'] / 12, 2, ',', '.') ?>
                        </p>
                    <?php else: ?>
                        <p class="price-current">
                            R$ <?= number_format((float)$produto['preco_venda'], 2, ',', '.') ?>
                        </p>
                        <p class="price-full">
                            ou 12x de R$ <?= number_format((float)$produto['preco_venda'] / 12, 2, ',', '.') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
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
                            <li><a href="../inicio/index.php">Início</a></li>
                            <li><a href="../sobre_nos/sobre_nos.php">Sobre nós</a></li>
                            <li><a href="../produto/produto.php">Produtos</a></li>
                            <li><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                            <li><a href="../perfil/perfil.php">Perfil</a></li>
                        </ul>
                    </nav>

                    <div class="social">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram">
                            <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook">
                            <i class="fa-brands fa-facebook" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-logo">
                    <img src="../../assetsGerais/aruanaCabecario.webp" alt="Logo Aruanã">
                </div>

                <div class="footer-legal">
                    <p>©<?= date('Y') ?> todos direitos reservados Aruanã</p>

                    <div class="footer-links">
                        <a href="../termos/termosdeuso.php">Termos de uso</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>
</body>

</html>
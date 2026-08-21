<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_produto, nome, preco_venda, preco_promocional, estoque, imagem FROM produtos");
$produtos = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ecotire</title>
    <!-- Link fonte Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Link API de icones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="main-content">
        <!-- Cabeçalho -->
        <header>
            <div class="header">
                <div class="header-top">
                    <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruanã"
                        onclick="window.location.href='../inicio/index.php'">

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
                <div class="header-bottom">
                    <nav>
                        <ul class="menu-horizontal" id="menu-links">
                            <li><a onclick="window.location.href='../inicio/index.php'">Início</a></li>
                            <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'">Sobre Nós</a></li>
                            <li><a onclick="window.location.href='../produto/produto.php'" class="ativo">Produtos</a>
                            </li>
                            <li class="contato-btn"><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <main class="container">
            <h1 class="main-title">Nossos Produtos Sustentáveis</h1>

            <section class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                <article class="product-card"
                    onclick="location.href='../pagina-produto-usuario/pagina-produto-usuario.php?id=<?php echo $produto['id_produto']; ?>'">
                    <div class="image-container">
                        <img src="../../assetsProdutos/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    </div>
                    <div class="product-info">
                        <h2 class="product-name"><?php echo $produto['nome']; ?></h2>
                            <?php if (!empty($produto['preco_promocional']) && $produto['preco_promocional'] < $produto['preco_venda']): ?>
                            <p class="price-old" style="text-decoration: line-through; color: #777; font-size: 0.9em; margin-bottom: 2px;">
                                De: R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                            </p>
                            <p class="price-current" style="font-weight: bold; color: #2e7d32; margin-top: 0;">
                                Por: R$ <?php echo number_format($produto['preco_promocional'], 2, ',', '.'); ?>
                            </p>    
                            <p class="price-full">
                                ou 12x de R$ <?php echo number_format($produto['preco_promocional'] / 12, 2, ',', '.'); ?>
                            </p>
                            <?php else: ?>
                            <p class="price-current" style="font-weight: bold; color: #2e7d32;">
                                R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                            </p>
                            <p class="price-full">
                                ou 12x de R$ <?php echo number_format($produto['preco_venda'] / 12, 2, ',', '.'); ?>
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
                        <li><a href='../inicio/index.php'>Início</a></li>
                        <li><a href='../sobre_nos/sobre_nos.php'>Sobre nós</a></li>
                        <li><a href="../produto/produto.php">Produtos</a></li>
                        <li><a href="#fale_conosco">Contato</a></li>
                        <li><a href='../perfil/perfil.php'>Perfil</a></li>
                    </ul>
                </nav>

                <div class="social">
                    <a href="https://instagram.com" title="Instagram"><i class="fa-brands fa-instagram"></i></i></a>
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="script.js"></script>
</body>

</html>
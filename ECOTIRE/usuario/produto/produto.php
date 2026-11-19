<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_produto, nome, descricao, preco_venda, preco_promocional, estoque, imagem FROM produtos");
$produtos = $stmt->fetchAll();

// Escapa texto vindo do banco antes de imprimir no HTML
function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function preco($valor)
{
    return number_format((float) $valor, 2, ',', '.');
}

$total = count($produtos);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Aruanã</title>
    <link rel="stylesheet" href="style.css">
    <!-- Link fonte Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Link API de icones -->
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

                    <i id="botaoCarrinho" class="fa-solid fa-cart-shopping icone-carrinho" title="Meu Carrinho"
                        role="link" tabindex="0" aria-label="Meu carrinho"></i>
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

    <main class="container">
        <div class="page-head">
            <div>
                <h1 class="main-title">Nossos Produtos Sustentáveis</h1>
                <p class="contagem" id="contagem" aria-live="polite">
                    <?php echo $total; ?> <?php echo $total === 1 ? 'produto' : 'produtos'; ?>
                </p>
            </div>

            <?php if ($total > 1): ?>
            <label class="ordenar">
                Ordenar por
                <select id="ordenar">
                    <option value="padrao">Mais recentes</option>
                    <option value="menor">Menor preço</option>
                    <option value="maior">Maior preço</option>
                    <option value="nome">Nome (A–Z)</option>
                </select>
            </label>
            <?php endif; ?>
        </div>

        <?php if ($total === 0): ?>
            <div class="vazio">
                <i class="fa-solid fa-seedling" aria-hidden="true"></i>
                Nenhum produto disponível no momento. Volte em breve!
            </div>
        <?php else: ?>
        <section class="grade" id="grade" aria-label="Lista de produtos">
            <?php foreach ($produtos as $i => $produto):
                $venda = (float) $produto['preco_venda'];
                $promo = (float) $produto['preco_promocional'];
                $emPromocao = $promo > 0 && $promo < $venda;
                $precoFinal = $emPromocao ? $promo : $venda;
                $estoque = (int) $produto['estoque'];

                // Etiqueta de estoque: ok / pouco / esg
                if ($estoque <= 0) {
                    $tagClasse = 'esg';
                    $tagTexto = 'Esgotado';
                } elseif ($estoque <= 5) {
                    $tagClasse = 'pouco';
                    $tagTexto = 'Poucas unidades';
                } else {
                    $tagClasse = 'ok';
                    $tagTexto = 'Em estoque';
                }
            ?>
            <a class="card"
                href="../pagina-produto-usuario/pagina-produto-usuario.php?id=<?php echo (int) $produto['id_produto']; ?>"
                data-ordem="<?php echo $i; ?>"
                data-preco="<?php echo e($precoFinal); ?>"
                data-nome="<?php echo e(mb_strtolower($produto['nome'], 'UTF-8')); ?>">

                <?php if (!empty($produto['imagem'])): ?>

                <?php
                    $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($produto['imagem']);

                    $imagemBase64 = base64_encode($produto['imagem']);
                ?>

                <img
                    src="data:<?php echo e($mime); ?>;base64,<?php echo $imagemBase64; ?>"
                    class="imagem-produto"
                    alt="<?php echo e($produto['nome']); ?>"
                    loading="lazy"
                >

                <span class="tag <?php echo $tagClasse; ?>">
                    <?php echo $tagTexto; ?>
                </span>
                <?php else: ?>
                    <div class="imagem-produto" aria-hidden="true">
                        <i class="fa-solid fa-leaf"></i>
                        <span class="tag <?php echo $tagClasse; ?>"><?php echo $tagTexto; ?></span>
                    </div>
                <?php endif; ?>

                <div class="corpo">
                    <h2><?php echo e($produto['nome']); ?></h2>
                    <p class="desc"><?php echo e($produto['descricao'] ?? ''); ?></p>

                    <div class="precos">
                        <?php if ($emPromocao): ?>
                            <span class="preco-orig">R$ <?php echo preco($venda); ?></span>
                        <?php endif; ?>
                        <span class="preco">R$ <?php echo preco($precoFinal); ?></span>
                    </div>

                    <span class="btn">Ver produto</span>
                </div>
            </a>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
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
                        <li><a href="../inicio/index.php#fale_conosco">Contato</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <script src="script.js"></script>
</body>

</html>
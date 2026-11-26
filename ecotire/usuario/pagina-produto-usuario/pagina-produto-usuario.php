<?php
include '../../funcoesPHP/connection.php';
include '../../funcoesPHP/imagemWebp.php';

function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function preco($valor)
{
    return number_format((float) $valor, 2, ',', '.');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$produto = null;

if ($id) {
    $stmt = $pdo->prepare(
        "SELECT id_produto, nome, descricao, modelo, preco_venda, preco_promocional, estoque, imagem
         FROM produtos WHERE id_produto = ? AND ativo = 1"
    );
    $stmt->execute([$id]);
    $produto = $stmt->fetch();
}

if (!$produto) {
    http_response_code(404);
} else {
    $venda      = (float) $produto['preco_venda'];
    $promo      = (float) $produto['preco_promocional'];
    $emPromocao = $promo > 0 && $promo < $venda;
    $precoFinal = $emPromocao ? $promo : $venda;
    $desconto   = $emPromocao ? (int) round((1 - $promo / $venda) * 100) : 0;
    $estoque    = (int) $produto['estoque'];

    if ($estoque <= 0) {
        $tagClasse = 'esg';
        $tagTexto  = 'Esgotado';
    } elseif ($estoque <= 5) {
        $tagClasse = 'pouco';
        $tagTexto  = 'Poucas unidades';
    } else {
        $tagClasse = 'ok';
        $tagTexto  = 'Em estoque';
    }

    // MEDIUMBLOB -> WebP (data URI)
    $imagemSrc = imagemParaDataUri($produto['imagem']);
}

session_start();
date_default_timezone_set('America/Sao_Paulo');

// Verificação da autenticação
$logado = isset($_SESSION['id_usuario']);

$nomeCompleto = $logado ? ($_SESSION['usuario_nome'] ?? '') : '';
$emailUsuario = $logado ? ($_SESSION['usuario_email'] ?? '') : '';

// Primeiro nome
$primeiroNome = '';
if ($nomeCompleto !== '') {
    $partesNome = preg_split('/\s+/', trim($nomeCompleto));
    $primeiroNome = $partesNome[0] ?? '';
}

// Saudação conforme o horário
$hora = (int) date('H');

if ($hora < 12) {
    $saudacao = 'Bom dia';
} elseif ($hora < 18) {
    $saudacao = 'Boa tarde';
} else {
    $saudacao = 'Boa noite';
}

// Proteção para exibição de dados
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto ? e($produto['nome']) . ' | Aruanã' : 'Produto não encontrado | Aruanã'; ?></title>
    <!-- Reaproveita header, footer e variáveis da página de produtos -->
    <link rel="stylesheet" href="../produto/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
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
                <?php if ($logado): ?>

                        <div class="user-menu" id="user-menu">

                            <button type="button"
                                class="user-greeting"
                                id="user-toggle"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="user-dropdown">

                                <i class="fa-solid fa-circle-user user-avatar"
                                    aria-hidden="true"></i>

                                <span class="user-greeting-text">
                                    <small><?= $saudacao ?>,</small>
                                    <strong><?= escapar($primeiroNome ?: 'Usuário') ?></strong>
                                </span>

                                <i class="fa-solid fa-chevron-down user-chevron"
                                    aria-hidden="true"></i>

                            </button>

                            <div class="user-dropdown" id="user-dropdown">

                                <a href="../perfil/perfil.php">
                                    <i class="fa-solid fa-user"></i>
                                    Meu perfil
                                </a>

                                <a href="../../funcoesPHP/logout.php" class="sair">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    Sair da conta
                                </a>

                            </div>

                        </div>

                    <?php else: ?>

                        <a href="../login/login.php" class="btn-entrar">
                            <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                            Entrar
                        </a>

                    <?php endif; ?>

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
                        <li><a href="../produto/produto.php" class="ativo">Produtos</a></li>
                        <li class="contato-btn"><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">

        <?php if (!$produto): ?>
            <div class="vazio vazio-produto">
                <i class="fa-solid fa-seedling" aria-hidden="true"></i>
                <p>Produto não encontrado ou indisponível.</p>
                <a class="btn-principal" href="../produto/produto.php">Ver todos os produtos</a>
            </div>
        <?php else: ?>
        <article class="detalhe">
            <div class="detalhe-imagem">
                <?php if ($imagemSrc): ?>
                    <img class="foto-produto" src="<?php echo e($imagemSrc); ?>" alt="<?php echo e($produto['nome']); ?>">
                <?php else: ?>
                    <div class="sem-foto" aria-hidden="true"><i class="fa-solid fa-leaf"></i></div>
                <?php endif; ?>
                <span class="tag <?php echo $tagClasse; ?>"><?php echo $tagTexto; ?></span>
                <?php if ($emPromocao): ?>
                    <span class="selo-desconto">-<?php echo $desconto; ?>%</span>
                <?php endif; ?>
            </div>

            <div class="detalhe-info">
                <p class="modelo"><?php echo e($produto['modelo']); ?></p>
                <h1 class="nome"><?php echo e($produto['nome']); ?></h1>

                <div class="precos-detalhe">
                    <?php if ($emPromocao): ?>
                        <span class="preco-orig">R$ <?php echo preco($venda); ?></span>
                    <?php endif; ?>
                    <span class="preco-final">R$ <?php echo preco($precoFinal); ?></span>
                </div>

                <p class="descricao"><?php echo e($produto['descricao']); ?></p>

                <ul class="beneficios">
                    <li><i class="fa-solid fa-leaf" aria-hidden="true"></i> Produto sustentável</li>
                    <li><i class="fa-solid fa-recycle" aria-hidden="true"></i> Reutilizável / biodegradável</li>
                </ul>

                <?php if ($estoque > 0): ?>
                <div class="compra" data-id="<?php echo (int) $produto['id_produto']; ?>" data-estoque="<?php echo $estoque; ?>">
                    <div class="quantidade" role="group" aria-label="Quantidade">
                        <button type="button" id="menos" aria-label="Diminuir quantidade">−</button>
                        <input type="number" id="qtd" value="1" min="1" max="<?php echo $estoque; ?>" aria-label="Quantidade">
                        <button type="button" id="mais" aria-label="Aumentar quantidade">+</button>
                    </div>

                    <button type="button" class="btn-principal" id="btnCarrinho">
                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Adicionar ao carrinho
                    </button>
                </div>
                <?php else: ?>
                    <p class="aviso-esgotado"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Produto esgotado no momento.</p>
                <?php endif; ?>

                <a class="btn-secundario" href="../produto/produto.php">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Continuar comprando
                </a>
            </div>
        </article>
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
    <!-- Menu, busca e ícone do carrinho do header (mesmo script da página de produtos) -->
    <script src="../produto/script.js"></script>
    <script src="script.js"></script>

</body>

</html>
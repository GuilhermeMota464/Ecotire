<?php
include '../../funcoesPHP/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "Produto não encontrado.";
    exit;
}

// LÓGICA DE VERIFICAÇÃO DE DESCONTO:
// Verifica se existe valor promocional e se ele é menor que o preço de venda
$temDesconto = !empty($produto['preco_promocional']) && $produto['preco_promocional'] < $produto['preco_venda'];

if ($temDesconto) {
    $precoFinal = $produto['preco_promocional'];
    // Calcula a porcentagem de desconto dinamicamente para exibir na tela
    $porcentagemDesconto = round((($produto['preco_venda'] - $produto['preco_promocional']) / $produto['preco_venda']) * 100);
} else {
    $precoFinal = $produto['preco_venda'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome']; ?> - Aruanã</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

<i class="fa-solid fa-arrow-left" onclick="window.location.href='../produto/produto.php'" style="cursor: pointer; margin: 20px; font-size: 1.5rem;"></i>

<div class="container">
    <div class="produtos-verticais">
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_2'; ?>.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
    </div>

    <div class="imagem-container">
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" id="produto-principal">
    </div>
    
    <div class="comprar-container">
        <form id="form-carrinho" action="../../funcoesPHP/addCarrinho.php" method="POST">

            <h1 class="produto-titulo"><?php echo $produto['nome']; ?></h1>

            <?php if ($temDesconto): ?>
                <p class="preco-antigo" style="text-decoration: line-through; color: #777;">
                    R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                </p>
                <div class="preco-area"> 
                    <span class="preco-atual">R$ <?php echo number_format($precoFinal, 2, ',', '.'); ?></span>
                    <span class="desconto" style="color: #2e7d32; font-weight: bold; margin-left: 10px;">
                        <?php echo $porcentagemDesconto; ?>% OFF
                    </span>
                </div>
            <?php else: ?>
                <div class="preco-area"> 
                    <span class="preco-atual">R$ <?php echo number_format($precoFinal, 2, ',', '.'); ?></span>
                </div>
            <?php endif; ?>

            <p class="frete" style="color: #2e7d32; font-weight: bold; margin-top: 15px;">Frete grátis</p>
            <p class="entrega-info">Chegará grátis amanhã</p>

            <p class="descricao" style="margin-top: 20px; color: #444;"><?php echo $produto['descricao']; ?></p>

            <div class="botoes-acao" style="margin-top: 25px;">
                <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto']; ?>">
                <input type="hidden" name="nome" value="<?php echo $produto['nome']; ?>">
                <input type="hidden" name="preco" value="<?php echo $precoFinal; ?>">

                <button type="submit" name="btn_carrinho" class="btn-comprar">
                    Adicionar ao Carrinho
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script.js"></script>
</body>
</html>
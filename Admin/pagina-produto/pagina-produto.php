<?php
include '../../funcoesPHP/connection.php';

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id_produto = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if ($produto = mysqli_fetch_assoc($resultado)) {
        // Produto encontrado
    } else {
        die("Produto não encontrado.");
    }

    mysqli_stmt_close($stmt);

} else {
    die("ID não informado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="produtos-verticais">
        <img src="../produtos/Assets/estojo2_1.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
        <img src="../produtos/Assets/estojo2_2.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
    </div>

    <div id="produto-principal-borda">
        <img src="../produtos/Assets/estojo2_1.webp" id="produto-principal">
    </div>
    
    <div class="comprar-container">
        <p class="vendas-info">Novo | +50mil vendidos</p>
        <h1><?php echo $produto['nome']; ?></h1>
        
        <p class="preco-antigo">R$180,90</p>
        <div class="preco-area">
            <span class="preco-atual">
                R$<?php echo number_format($produto['preco'], 2, ',', '.'); ?>
            </span>
            <span class="desconto">36% OFF</span>
        </div>

        <p class="frete-gratis">Frete grátis</p>
        <p class="entrega-info">Chegará grátis amanhã</p>

        <div class="botoes-acao">
            <button class="btn-comprar">Comprar agora</button>
            <button class="btn-carrinho">Adicionar ao carrinho</button>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
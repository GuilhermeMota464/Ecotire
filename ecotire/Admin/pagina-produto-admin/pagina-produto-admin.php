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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
    <link rel="stylesheet" href="style.css">
    <!-- Link fonte Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Link API de icones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icone da aba no navegador -->
     <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

<div class="container">
    <div class="produtos-verticais">
        <img src="../produtos/Assets/estojo1_1.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
        <img src="../produtos/Assets/estojo1_2.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
    </div>

    <img src="../produtos/Assets/estojo1_1.webp" id="produto-principal">
    
    <div class="comprar-container">
        <p class="vendas-info">Novo | 0 vendidos</p>
    <form action="../../funcoesPHP/editionProduct.php" method="POST">
        <input name="id" hidden value="<?php $_GET['id'] ?>">
        <textarea><?php echo $produto['nome']; ?></textarea>
        
        <input name="preco" class="preco-antigo" value="R$<?php echo $produto['preco'] ?>"></input>
        <div class="preco-area"> 
            <p class="preco-atual">R$<?php echo $produto['preco'] ?></p>
            <input type="text" name="promo" class="desconto" value="<?php echo $produto['promo'] ?>" style="width: 21px;"></input>  
            <p class="desconto">% OFF</p>
        </div>

        <p class="frete">Frete grátis</p>
        <p class="entrega-info">Chegará grátis amanhã</p>

        <div class="botoes-acao">
            <input type="submit" class="btn-comprar"></button>
        </form>
            <button class="btn-carrinho">Resetar</button>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
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
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_2'; ?>.webp" class="produtos-imagem" onclick="ChangeImage(this.src)">
    </div>

    <div class="imagem-container">
        <img src="../produto/Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" id="produto-principal">
    </div>

    <div class="comprar-container">
        <form action="../../funcoesPHP/edit.php" method="POST">

            <h1 class="produto-titulo"><?php echo $produto['nome']; ?></h1>

            <p class="preco-antigo">R$ <?php echo $produto['preco']; ?></p>

            <div class="preco-area"> 
                <span class="preco-atual">R$ <?php echo $produto['preco']; ?></span>
                <span class="desconto"><?php echo $produto['promo']; ?>% OFF</span>
            </div>

            <p class="frete">Frete grátis</p>
            <p class="entrega-info">Chegará grátis amanhã</p>

            <div class="botoes-acao">
                <button type="submit" class="btn-comprar">
                    Comprar agora
                </button>
            </div>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
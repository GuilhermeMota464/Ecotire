<?php
include '../../funcoesPHP/connection.php';

$sql = "SELECT id_produto, nome, preco, estoque, imagem  FROM produtos";
$result = mysqli_query($conn, $sql);

$produtos = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row;
    }
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="main-content">
<!-- Cabeçalho -->
    <header>
        <div class="header">
         <div class="header-top">
            <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire" onclick="window.location.href='admin.php'">           
            <img src="../../assetsGerais/fotoPerfil.webp" class="foto_perfil" alt="Foto de Perfil">
         </div>
        <div class="header-bottom">
            <nav>
            <ul class="menu-horizontal">
            <li><a onclick="window.location.href='../inicio-admin/inicio-admin.php'">Pedidos</a></li>
            <li><a onclick="window.location.href='../produtos/produtos-admin.php'" style="background-color: rgb(222, 217, 217); border-radius: 5px;">Produtos</a></li>
            <li><a onclick="window.location.href='../inicio/index.php'" >Usuários</a></li>
            </ul>
            </nav>
           </div>
        </div>
    </header>

    <div class="container">
        <h1>Produtos</h1>
        <div class="product-list">

    <main class="container">
        <section class="products-grid">
            <?php foreach ($produtos as $produto): ?>
                
                <article class="product-card">
                    <div class="image-container">
                        <img src="../produtos/Assets/<?php echo $produto['imagem'] . '_1' ?>.webp" alt="<?php echo $produto['nome']; ?>">
                    <div class="product-info">
                        <h2 class="product-name"><?php echo $produto['nome']; ?></h2>
                        <p class="price-pix">
                            <?php echo 'R$', number_format(floatval(str_replace(['R$', ','], ['', '.'], $produto['preco'])));?> <span>no Pix</span>
                        </p>
                        <p class="price-full">
                            ou <?php echo 'R$', number_format(floatval(str_replace(['R$', ','], ['', '.'], $produto['preco']))); ?> em 12x de <?php echo 'R$', number_format(floatval(str_replace(['R$', ','], ['', '.'], $produto['preco'])) / 12, 2, ',', '.'); ?>
                        </p>
                     <div class="div-botoes">
                        <button class="edit-button" onclick="window.location.href='../pagina-produto/pagina-produto.php?produto=<?php echo urlencode($produto['nome']); ?>&id=<?php echo $produto['id_produto']; ?>'">Editar</button>
                        <button class="delete-button" onclick="if(confirm('Tem certeza que deseja excluir este produto?')) window.location.href='../../funcoesPHP/remove.php?delete_id=<?php echo $produto['id_produto']; ?>'">Excluir</button>
                     </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

    </main>
        </div>
    </div>
</body>
</html>
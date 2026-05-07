<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_produto, nome, preco, estoque, imagem  FROM produtos");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icone da aba no navegador -->
     <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
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
            <li><a onclick="window.location.href='../usuarios/usuarios.php'" >Usuários</a></li>
            </ul>
            </nav>
           </div>
        </div>
    </header>

    <div class="container">
        <h1>Produtos</h1>
        <button id="openModalBtn" class="add-product-btn">+ Adicionar Produto</button>
        <div class="modal-backdrop" id="modalBackdrop">
            
<div id="productModal" class="modal">
    <button class="modal-close" type="button">&times;</button>
    
    <form method="post" action="../../funcoesPHP/submit.php" enctype="multipart/form-data">
            <div class="modal-column-left">
                <h2>Cadastrar Produtos</h2>
                <div class="field-container">
                    <input type="text" name="nome" placeholder=" " id="nome" class="input" required>
                    <label for="nome">NOME</label>
                </div>

                <div class="field-container">
                    <input type="number" name="preco" step="0.01" placeholder=" " id="preco" class="input" required>
                    <label for="preco">PREÇO</label>
                </div>

                <div class="field-container">
                    <input type="number" name="estoque" placeholder=" " id="estoque" class="input" required>
                    <label for="estoque">ESTOQUE</label>
                </div>

                <div class="promo-box">
                    <label for="promo">PROMOÇÃO</label>
                    <input type="checkbox" name="promo" id="promo">

                    <div class="field-container hidden-field" id="container-preco-promo">
                        <div class="percentage-wrapper">
                            <input type="number" name="preco_promocional" step="1" max="100" placeholder=" " id="preco_promo" class="input">
                            <label for="preco_promo" id="label_promo"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-column-right">
                <div class="image-upload-section">
                    <input type="file" name="imagem" accept="image/*" id="inserir-imagem" hidden>
                    
                    <label for="inserir-imagem" class="picture" tabindex="0">
                        <span class="picture-image">img</span>
                    </label>
                </div>

                <div class="field-container">
                    <textarea id="descricao_imagem" name="descricao" placeholder=" " required></textarea>
                    <label for="descricao_imagem" id="label-descricao-imagem">DESCRIÇÃO</label>
                </div>
            </div>

            <button type="submit" class="btn-submit">CADASTRAR PRODUTO</button>
        </form>
    </div>
</div>

   <div class="product-list">   
    <main class="container">
        <section class="products-grid">
            <?php foreach ($produtos as $produto): ?>
                <article class="product-card">
                    <div class="image-container">
                        <img src="../produtos/Assets/<?php echo $produto['imagem'] . '_1' ?>.webp" alt="<?php echo $produto['nome']; ?>">
                    </div>
                    <div class="product-info">
                        <span class="tag-mais-vendido">MAIS VENDIDO</span>
                        <h3 class="product-name"><?php echo $produto['nome']; ?></h3>
                        <p class="price-old">R$ <?php echo number_format($produto['preco'] * 1.2, 2, ',', '.'); ?></p>
                        <div class="price-row">
                            <span class="price-current">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                            <span class="discount-tag">20% OFF</span>
                        </div>
                        <p class="installments">12x R$ <?php echo number_format($produto['preco'] / 12, 2, ',', '.'); ?></p>
                        <p class="shipping-info">Chegará grátis amanhã</p>
                    <div class="div-botoes">
                        <button class="edit-button" onclick="window.location.href='../pagina-produto-admin/pagina-produto-admin.php?produto=<?php echo urlencode($produto['nome']); ?>&id=<?php echo $produto['id_produto']; ?>'">Editar</button>
                        <button class="delete-button" onclick="if(confirm('Tem certeza que deseja excluir este produto?')) window.location.href='../../funcoesPHP/remove.php?delete_id=<?php echo $produto['id_produto']; ?>'">Excluir</button>
                    </div>    
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
   </div>
  </div>
 </div>

<script src="script.js"></script>
</body>
</html>
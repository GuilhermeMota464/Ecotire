<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_produto, nome, preco, estoque, imagem FROM produtos");
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
</head>
 <body>
<div class="main-content">
<!-- Cabeçalho -->
<header>
    <div class="header">
     <div class="header-top">
            <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire">
          <div class="search-group">               
            <input type="text" class="search-bar" id="busca" placeholder="Pesquisar...">
            <i class="fa-solid fa-magnifying-glass" id="lupa"></i>                
            <div id="resultado"></div>
          </div>
          
          <div class="header-actions">
            <i onclick= "window.location.href = '../login/login.php'" class="fa-solid fa-user foto_perfil"></i>
            <i id="botaoCarrinho" class="fa-solid fa-cart-shopping" title="Meu Carrinho"></i>
          </div>       
     </div>
    <div class="header-bottom">
        <nav>
          <ul class="menu-horizontal" id="menu-links">
            <li><a onclick="window.location.href='../inicio/index.php'">Inicio</a></li>
            <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'" >Sobre</a></li>
            <li><a onclick="window.location.href='../produto/produto.php'" style="background-color: rgb(222, 217, 217); border-radius: 5px;">Produtos</a></li>
            <li class="contato" onclick="window.location.href='../inicio/index.php #fale_conosco'"><a>Contato</a></li>
          </ul>
        </nav>
    </div>
</header>

<main class="container">
 <h1 class="main-title">Estojos</h1>
  
    <section class="products-grid">
        <?php foreach ($produtos as $produto): ?>
            <article class="product-card" onclick="window.location.href='../pagina-produto-usuario/pagina-produto-usuario.php?produto=<?php echo urlencode($produto['nome']); ?>&id=<?php echo $produto['id_produto']; ?>'">
                <div class="image-container">
                    <i class="fa-regular fa-heart wishlist" onclick="favoritarProduto(this)"></i>
                    <img src="Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" alt="<?php echo $produto['nome']; ?>">
                   <div class="product-info">
                    <h2 class="product-name"><?php echo $produto['nome']; ?></h2>
                    <p class="price-pix">
                        <?php echo $produto['preco']; ?> <span>no Pix</span>
                    </p>
                    <p class="price-full">
                        ou <?php echo $produto['preco']; ?> em 12x de <?php echo 'R$', number_format(floatval(str_replace(['R$', ','], ['', '.'], $produto['preco'])) / 12, 2, ',', '.'); ?>
                    </p>
                 </div>
                </div>
            </article>

        <?php endforeach; ?>
    </section>
</main>


    <footer>
<section class="footer">
      <div class="footer-row">
        <div class="footer-col">
          <h4>Informação</h4>
          <ul class="links">
            <li><a href="../inicio/index.php">Inicio</a></li>
            <li><a href="../sobre_nos/sobre_nos.html">Sobre nós</a></li>
            <li><a href="#">Produtos</a></li>
            <li><a href="#">Contatos</a></li>
            <li><a href="#">Cadastro</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Explore</h4>
          <ul class="links">
            <li><a href="#">Free Designs</a></li>
            <li><a href="#">Latest Designs</a></li>
            <li><a href="#">Themes</a></li>
            <li><a href="#">Popular Designs</a></li>
            <li><a href="#">Art Skills</a></li>
            <li><a href="#">New Uploads</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <p>
            RedRoosterTI é um dos principais 
            fornecedores de serviços de design e 
            desenvolvimento web. Criamos sites 
            incríveis que ajudam empresas a 
            estabelecer uma forte presença online. 
          </p>
        </div>
        <div class="footer-col">
          <h4>Newsletter</h4>
          <p>
            Subscribe to our newsletter for a weekly dose
            of news, updates, helpful tips, and
            exclusive offers.
          </p>
          <form action="#">
            <input type="text" placeholder="Your email" required>
            <button type="submit">INSCREVA-SE</button>
          </form>
          <div class="icons">
            <i class="fa-brands fa-facebook-f"></i>
            <i class="fa-brands fa-twitter"></i>
            <i class="fa-brands fa-instagram"></i>
            <i class="fa-brands fa-github"></i>
          </div>
        </div>
      </div>
    </section>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script.js"></script>  
</body>
</html>
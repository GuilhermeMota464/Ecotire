  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
      <link rel="stylesheet" href="style.css">
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Sobre nós</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  </head>
<body>
  <!-- Cabeçalho -->
<header>
    <div class="header">
        <div class="header-top">
            <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruanã" onclick="window.location.href='../inicio/index.php'">
            
            <div class="search-group">               
                <input type="text" class="search-bar" id="busca" placeholder="Pesquisar soluções sustentáveis...">
                <button type="button" id="btn-busca"><i class="fa-solid fa-magnifying-glass" id="lupa"></i></button>
                <div id="resultado"></div>
            </div>
 
            <div class="header-actions">
                <i onclick="window.location.href = '../perfil/perfil.php'" class="fa-solid fa-circle-user" title="Minha Conta"></i>
                <i onclick="window.location.href = '../carrinho/carrinho.php'" class="fa-solid fa-cart-shopping" title="Meu Carrinho"></i>
            </div>
        </div>
        <div class="header-bottom">
            <nav>
                <ul class="menu-horizontal" id="menu-links">
                    <li><a onclick="window.location.href='../inicio/index.php'">Início</a></li>
                    <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'" class="ativo">Sobre Nós</a></li>
                    <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
                    <li class="contato-btn"><a href="#fale_conosco">Contato</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main>
    <section class="sobre_container">
        <div class="texto_sn">
            <h1>Sobre nós</h1>
            <p>Somos uma equipe apaixonada por design sustentável e inovação com propósito. Acreditamos que é possível repensar a forma como produzimos e consumimos, dando novos significados a materiais que seriam descartados.</p>
            <br>
            <p>Criamos estojos ecológicos feitos com câmaras de ar de pneus reutilizadas. Cada peça carrega uma história de transformação e respeito pelo planeta.</p>
        </div>
        <img src="../../assetsGerais/placeholder.webp" class="foto_equipe" alt="Foto da nossa equipe">
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
<script src="script.js"></script>
  </body>
  </html>


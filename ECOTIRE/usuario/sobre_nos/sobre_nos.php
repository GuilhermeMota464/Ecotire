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
                    <li class="contato-btn"><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="container-sobre">
    <section class="about-section">
        <div class="about-content">
            <span class="subtitle">SOBRE NÓS</span>
            <h1>Ajudando empresas<br>a crescer com <span>sustentabilidade.</span></h1>
            <p>
              A Aruanã não é apenas uma loja; é um movimento. Curamos e entregamos as melhores 
              soluções sustentáveis para que você e sua empresa possam prosperar sem abrir mão 
              do cuidado com o planeta.
            </p>
            <button class="btn-cta" onclick="window.location.href='../produto/produto.php'">Ver Nossas Soluções</button>
        </div>

        <div class="about-image-wrapper">
            <div class="shape-bg"></div>
            <div class="image-container">
                <img src="equipe.jpg" alt="Nossa Equipe">
            </div>
            <div class="dots-pattern"></div>
        </div>
    </section>

  <section class="reviews-section">
      <div class="reviews-header">
          <h2>O que nossos clientes dizem</h2>
          <p>A confiança de quem já transformou seu consumo com a Aruanã.</p>
      </div>

      <div class="reviews-container">
          <div class="review-card">
              <div class="stars">★★★★★</div>
              <h4>"Experiência incrível!"</h4>
              <p>"Fiquei impressionada com o cuidado da Aruanã em cada detalhe. O produto tem um acabamento impecável e une estética com materiais sustentáveis."</p>
              <span class="review-author">Mariana Silva</span>
              <span class="author-job">Designer de Interiores</span>
          </div>

          <div class="review-card">
              <div class="stars">★★★★★</div>
              <h4>"Rapidez e Consciência"</h4>
              <p>"Comprei soluções para o meu escritório e a entrega foi rápida, com embalagem eco-friendly. Atendimento humano e muito eficiente."</p>
              <span class="review-author">Ricardo Gomes</span>
              <span class="author-job">Empreendedor</span>
          </div>

          <div class="review-card">
              <div class="stars">★★★★★</div>
              <h4>"Consumo com Propósito"</h4>
              <p>"É difícil encontrar um site de vendas com uma curadoria tão criteriosa. Sinto que cada centavo investido aqui apoia um futuro mais verde."</p>
              <span class="review-author">Beatriz Soares</span>
              <span class="author-job">Consultora Ambiental</span>
          </div>
      </div>
  </section>

</main>

<footer class="footer">
    <div class="footer-container">

        <div class="footer-left">
            <h2>
                O futuro começa nas escolhas de hoje.<br>
                A Aruanã oferece soluções sustentáveis para um dia a dia mais responsável.
            </h2>

            <div class="footer-logo">
                <img src="../../assetsGerais/aruanaCabecario.webp">   
            </div>
        </div>

        <div class="footer-right">

            <nav>
                <ul>
                    <li><a href='../inicio/index.php'>Início</a></li>
                    <li><a href='../sobre_nos/sobre_nos.php'>Sobre nós</a></li>
                    <li><a href="'../produto/produto.php'">Produtos</a></li>
                    <li><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                    <li><a href= '../perfil/perfil.php'>Perfil</a></li>
                </ul>
            </nav>


            <div class="footer-bottom">
                <p>©<?php echo date('Y') ?> todos direitos reservados Aruanã</p>

                <div class="footer-links">
                    <a href="../termos/termosdeuso.php">Termos de uso</a>
                </div>
            </div>

        </div>

    </div>
</footer>

<script src="script.js"></script>
  </body>
  </html>


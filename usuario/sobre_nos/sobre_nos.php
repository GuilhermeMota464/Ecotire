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
    <script src="script.js"></script>
  <header>
      <div class="header">
      <div class="header-top">
              <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire" onclick="window.location.href='../cadastro/index.php'">
              <div class="search-group">
              <input type="text" class="search-bar" placeholder="Pesquisar...">
              <i class="fa-solid fa-magnifying-glass"></i>                
              </div>

              <i onclick="window.location.href='../login/login.php'" class="fa-solid fa-user foto_perfil"></i>
      </div>
      <div class="header-bottom">
        <nav>
          <ul class="menu-horizontal" id="menu-links">
            <li><a onclick="window.location.href='../inicio/index.php'">Inicio</a></li>
            <li><a style="background-color: rgb(222, 217, 217); border-radius: 5px;">Sobre</a></li>
            <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
            <li class="contato"><a>Contato</a></li>
          </ul>

          <a class="icon" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
          </a>
        </nav>
       </div>
      </div>
  </header>
  <main>
      <section>
  <h1>Sobre nós</h1>

  <div class="sobre_nos">
      <p class="texto_sn">Somos uma equipe apaixonada por design sustentável e inovação com propósito. Acreditamos que é possível repensar a forma como produzimos e consumimos, dando novos significados a materiais que, de outra forma, seriam descartados.

  É por isso que criamos estojos ecológicos feitos com câmaras de ar de pneus reutilizadas.<br> Cada peça que produzimos carrega uma história: de transformação, consciência ambiental e respeito pelo planeta.

  Nosso processo é totalmente artesanal, valorizando o trabalho manual, a originalidade e o cuidado com os detalhes. Ao escolher um dos nossos produtos, você apoia um movimento que une sustentabilidade, criatividade e responsabilidade social.

  Mais do que estojos, criamos soluções com impacto positivo — para o meio ambiente e para as pessoas</p>
    
  </div>
  <img src="../../assetsGerais/placeholder.webp" height="300px" width="400px" class="foto_equipe">        
      </section>

  <section class="equipe">
    <h2>Nossa Equipe</h2>
    <div class="container-equipe">
      <div class="membro">
        <img src="../../assetsGerais/rafaela.webp" alt="Foto do membro da equipe">
        <h3>Rafaela Teles</h3>
        <p>Diretora Geral</p>
      </div>
      <div class="membro">
        <img src="../../assetsGerais/clara.webp" alt="Foto do membro da equipe">
        <h3>Clara</h3>
        <p>Marketing</p>
      </div>
      <div class="membro">
        <img src="../../assetsGerais/andre.webp" alt="Foto do membro da equipe">
        <h3>André Victor</h3>
        <p>Financeiro </p>
      </div>
      <div class="membro">
        <img src="../../assetsGerais/miguel.webp" alt="Foto do membro da equipe">
        <h3>Miguel Valada</h3>
        <p>Distribuição </p>
      </div>
            <div class="membro">
        <img src="../../assetsGerais/emily.webp" alt="Foto do membro da equipe">
        <h3>Emily</h3>
        <p>Designer </p>
      </div>
            <div class="membro">
        <img src="../../assetsGerais/adriele.webp" alt="Foto do membro da equipe">
        <h3>Adriele</h3>
        <p>Vendas</p>
      </div>
            <div class="membro">
        <img src="../../assetsGerais/livia.webp" alt="Foto do membro da equipe">
        <h3>Livia</h3>
        <p>Montagem</p>
      </div>
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
  </body>
  </html>
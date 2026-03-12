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
<!-- Cabeçalho -->
<header>
    <div class="header">
     <div class="header-top">
            <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire">
            <div class="search-group">
            <input type="text" class="search-bar" placeholder="Pesquisar...">
            <i class="fa-solid fa-magnifying-glass"></i>                
          </div>
          
          <i onclick="window.location.href'../login/login.php'" class="fa-solid fa-user foto_perfil"></i>

     </div>
    <div class="header-bottom">
        <nav>
          <ul class="menu-horizontal" id="menu-links">
            <li><a onclick="window.location.href='#'" style="background-color: rgb(222, 217, 217); border-radius: 5px;">Inicio</a></li>
            <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'">Sobre</a></li>
            <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
            <li class="contato"><a>Contato</a></li>
          </ul>

         <a class="icon" id="icon" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
         </a>

        </nav>
        </div>
    </div>
</header>
<div class="main-content">
<!-- Foto que alterna -->
<img src="../../assetsGerais/placeholder.webp" class="imagem_principal" alt="Imagem Principal">

<div class="fale_conosco_container">
 <form class="fale_conosco_form">
  <div class="form_title">
   <h2>Fale Conosco</h2>
  </div>
  <div class="form_groups">
  <div class="input_group">
<div class="conjunto">
   <label for="nome" title="Nome"><i class="fa-solid fa-user"></i></label>
   <input type="text" id="nome" name="nome" required placeholder="Nome">
</div>
<div class="conjunto">   <label for="email" title="Email"><i class="fa-solid fa-envelope"></i></label>
   <input type="email" id="email" name="email"  placeholder="E-mail"></div>
<div class="conjunto">   <label for="telefone"  title="Telefone"><i class="fa-solid fa-phone"></i></label>
   <input type="tel" id="telefone" name="telefone" required placeholder="Telefone"></div>
  </div>
  <div class="mensagem_group">

    <label for="mensagem" class="message-title"><i class="fa-solid fa-message"></i>Mensagem</label>
    <textarea id="mensagem" name="mensagem" rows="4" required></textarea>

  </div>
  </div>
  <button id="enviar_mensagem" type="submit">Enviar</button>
 </form>
</div>
</div>
<footer>
<section class="footer">
      <div class="footer-row">
        <div class="footer-col">
          <h4>Informação</h4>
          <ul class="links">
            <li><a href="../inicio/index.php">Inicio</a></li>
            <li><a href="../sobre_nos/sobre_nos.html">Sobre nós</a></li>
            <li><a href="../produto/produto.php">Produtos</a></li>
            <li><a href="#">Contatos</a></li>
            <li><a href="#">Cadastro</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Explore</h4>
          <ul class="links">
            <li><a href="../termos/termosdeuso.php">Termos e condição</a></li>
            <li><a href="#">Contato</a></li>
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
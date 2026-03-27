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
            </div>

            <img src="../../assetsGerais/fotoPerfil.webp" class="foto_perfil" alt="Foto de Perfil">
    </div>
    <div class="header-bottom">
        <nav>
         <ul class="menu-horizontal">
          <li><a onclick="window.location.href='#'"style="background-color: rgb(222, 217, 217); border-radius: 5px;">Pedidos</a></li>
          <li><a onclick="window.location.href='../produtos/produtos-admin.php'" >Produtos</a></li>
          <li><a onclick="window.location.href='../inicio/index.php'" >Usuários</a></li>
         </ul>
        </nav>
        </div>
    </div>
</header>

<div class="sales-container">
    <div class="sales-content">
        <h1>Tabela de vendas</h1>
        <div id="sales-chart"></div>
    </div>

    <div class="gender-content">
        <h1>Gênero dos clientes</h1>
        <div id="gender-chart"></div>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>
</body>
</html>
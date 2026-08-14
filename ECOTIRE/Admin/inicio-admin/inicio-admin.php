<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aruanã</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="httpeseewews://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <div class="header">
            <div class="header-top">
                <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruanã"
                     onclick="window.location.href='../inicio/index.php'">

                <div class="search-group">
                    <input type="text" class="search-bar" id="busca" placeholder="Pesquisar soluções sustentáveis...">
                    <button type="button" id="btn-busca"><i class="fa-solid fa-magnifying-glass" id="lupa"></i></button>
                    <div id="resultado"></div>
                </div>

                <div class="header-actions">
                    <i onclick="window.location.href = '../perfil/perfil.php'" class="fa-solid fa-circle-user"
                       title="Minha Conta"></i>
                </div>
            </div>

            <div class="header-bottom">
                <button id="icon" class="hamburger" aria-label="Abrir menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <nav>
                    <ul class="menu-horizontal" id="menu-links">
                        <li><a onclick="window.location.href='../inicio/index.php'" class="ativo">Início</a></li>
                        <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="sales-container">
        <section class="sales-content">
            <h1>Relatório Mensal de Vendas</h1>
            <div class="chart-wrapper">
                <canvas id="sales-chart"></canvas>
            </div>
        </section>

        <section class="gender-content">
            <h1>Distribuição por Gênero</h1>
            <div class="chart-wrapper">
                <canvas id="gender-chart"></canvas>
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
                    <img src="../../assetsGerais/aruanaCabecario.webp" alt="Logo Aruanã">
                </div>
            </div>

            <div class="footer-right">
                <nav>
                    <ul>
                        <li><a href="#">Início</a></li>
                        <li><a href="#">Sobre nós</a></li>
                        <li><a href="#">Produtos</a></li>
                        <li><a href="#">Contato</a></li>
                        <li><a href="#">Perfil</a></li>
                    </ul>
                </nav>

                <div class="footer-bottom">
                    <p>©<?php echo date('Y') ?> todos direitos reservados Aruanã</p>
                    <div class="footer-links">
                        <a href="#">Termos de uso</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>
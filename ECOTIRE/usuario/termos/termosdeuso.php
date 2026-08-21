<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aruanã | Termos de Uso</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Cabeçalho -->
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
                    <i onclick="window.location.href = '../perfil/perfil.php'" class="fa-solid fa-circle-user icone-perfil"
                        title="Minha Conta"></i>
                    <button id="icon" class="hamburger" aria-label="Abrir menu" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <i onclick="window.location.href = '../carrinho/carrinho.php'" class="fa-solid fa-cart-shopping"
                        title="Meu Carrinho"></i>
                </div>
            </div>

            <div class="header-bottom">
                <nav>
                    <ul class="menu-horizontal" id="menu-links">
                        <li><a onclick="window.location.href='../inicio/index.php'">Início</a></li>
                        <li><a onclick="window.location.href='../sobre_nos/sobre_nos.php'">Sobre Nós</a></li>
                        <li><a onclick="window.location.href='../produto/produto.php'">Produtos</a></li>
                        <li class="contato-btn"><a onclick="window.location.href='../inicio/index.php#fale_conosco'">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="termos-container">
            <div class="termos-title">
                <h1>Termos e Condições de Uso</h1>
                <p>Última atualização: 06 de fevereiro de 2026</p>
            </div>

            <div class="termos-body">
                <p>
                    Este documento estabelece os Termos e Condições de Uso do site da Aruanã.
                    Ao acessar e utilizar este site, o usuário declara que leu, compreendeu e
                    concorda integralmente com as disposições aqui previstas.
                </p>

                <h2>1. Sobre a Aruanã</h2>
                <p>
                    A Aruanã é uma marca voltada à produção e comercialização de estojos sustentáveis
                    fabricados a partir de pneus reciclados, promovendo a reutilização de materiais
                    e contribuindo para práticas ambientalmente responsáveis.
                </p>

                <h2>2. Aceitação dos Termos</h2>
                <p>
                    O acesso e uso deste site implicam na aceitação plena e sem reservas destes
                    Termos e Condições de Uso. Caso o usuário não concorde com qualquer cláusula
                    aqui prevista, deverá abster-se de utilizar o site.
                </p>

                <h2>3. Cadastro e Responsabilidade do Usuário</h2>
                <p>O usuário compromete-se a:</p>
                <ul>
                    <li>Fornecer informações verdadeiras, completas e atualizadas;</li>
                    <li>Manter a confidencialidade de seus dados de acesso;</li>
                    <li>Não utilizar o site para fins ilícitos, fraudulentos ou que violem a legislação vigente;</li>
                    <li>Respeitar os direitos da Aruanã e de terceiros.</li>
                </ul>

                <h2>4. Produtos</h2>
                <p>
                    Os produtos comercializados pela Aruanã são confeccionados com materiais reciclados,
                    podendo apresentar variações naturais de cor, textura e acabamento. Tais variações
                    não configuram defeito, mas características inerentes ao processo sustentável de produção.
                </p>
                <p>
                    As imagens disponibilizadas no site possuem caráter meramente ilustrativo.
                </p>

                <h2>5. Preços e Pagamentos</h2>
                <p>
                    Os preços dos produtos estão sujeitos a alteração sem aviso prévio,
                    respeitados os pedidos já confirmados.
                </p>
                <p>
                    A confirmação do pedido está condicionada à aprovação do pagamento pela
                    instituição financeira ou operadora responsável.
                </p>
                <p>
                    A Aruanã reserva-se o direito de cancelar pedidos em caso de suspeita
                    de fraude ou inconsistência nas informações fornecidas.
                </p>

                <h2>6. Entrega</h2>
                <p>
                    O prazo de entrega varia conforme a localidade do cliente e a modalidade
                    de envio selecionada.
                </p>
                <p>
                    A Aruanã não se responsabiliza por atrasos decorrentes de fatores externos,
                    tais como greves, condições climáticas adversas, falhas logísticas ou
                    situações de força maior.
                </p>

                <h2>7. Trocas e Devoluções</h2>
                <p>
                    O cliente poderá solicitar a devolução do produto no prazo de até
                    <strong>7 (sete) dias corridos</strong> após o recebimento, conforme previsto
                    no Código de Defesa do Consumidor.
                </p>
                <p>
                    Em caso de defeito de fabricação ou envio incorreto, a Aruanã providenciará
                    a substituição ou reembolso, conforme análise do caso.
                </p>
                <p>
                    O produto deverá ser devolvido em sua embalagem original, sem indícios de uso indevido.
                </p>

                <h2>8. Propriedade Intelectual</h2>
                <p>
                    Todo o conteúdo deste site, incluindo textos, imagens, logotipos, marcas,
                    layout, design e demais elementos, é de propriedade exclusiva da Aruanã
                    e encontra-se protegido pela legislação de direitos autorais e propriedade intelectual.
                </p>
                <p>
                    É proibida a reprodução, distribuição ou utilização de qualquer conteúdo
                    sem autorização prévia e expressa.
                </p>

                <h2>9. Proteção de Dados e Privacidade</h2>
                <p>
                    Os dados pessoais fornecidos pelos usuários serão tratados em conformidade
                    com a legislação vigente, especialmente a Lei Geral de Proteção de Dados (LGPD).
                </p>
                <p>
                    As informações coletadas destinam-se exclusivamente ao processamento de pedidos,
                    atendimento ao cliente e comunicação institucional.
                </p>

                <h2>10. Limitação de Responsabilidade</h2>
                <p>
                    A Aruanã não se responsabiliza por danos decorrentes do uso indevido do site,
                    falhas técnicas externas, indisponibilidade temporária da plataforma ou
                    eventos de caso fortuito ou força maior.
                </p>

                <h2>11. Alterações dos Termos</h2>
                <p>
                    A Aruanã poderá modificar estes Termos e Condições de Uso a qualquer momento,
                    sendo responsabilidade do usuário consultá-los periodicamente.
                </p>

                <h2>12. Legislação Aplicável e Foro</h2>
                <p>
                    Estes Termos são regidos pela legislação brasileira.
                    Fica eleito o foro da comarca do domicílio da empresa para dirimir
                    quaisquer controvérsias decorrentes deste documento, salvo disposição legal em contrário.
                </p>

                <h2>13. Contato</h2>
                <p>
                    Para esclarecimento de dúvidas ou solicitações, entre em contato pelo e-mail:
                    <a href="mailto:aruana@gmail.com">aruana@gmail.com</a>
                </p>
            </div>
        </section>
    </main>

    <button id="btnTopo" title="Voltar ao topo"><i class="fa-solid fa-arrow-up"></i></button>

    <footer class="footer">
        <div class="footer-container">

            <div class="footer-top">
                <div class="footer-left">
                    <p class="footer-mensagem">
                        O futuro começa nas escolhas de hoje.<br>
                        A Aruanã torna esse caminho mais simples.
                    </p>
                </div>

                <div class="footer-right">
                    <nav>
                        <ul>
                            <li><a href='../inicio/index.php'>Início</a></li>
                            <li><a href='../sobre_nos/sobre_nos.php'>Sobre nós</a></li>
                            <li><a href="../produto/produto.php">Produtos</a></li>
                            <li><a href="../inicio/index.php#fale_conosco">Contato</a></li>
                            <li><a href='../perfil/perfil.php'>Perfil</a></li>
                        </ul>
                    </nav>

                    <div class="social">
                        <a href="https://instagram.com" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://facebook.com" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-logo">
                    <img src="../../assetsGerais/aruanaCabecario.webp" alt="Logo Aruanã">
                </div>

                <div class="footer-legal">
                    <p>©<?php echo date('Y') ?> todos direitos reservados Aruanã</p>

                    <div class="footer-links">
                        <a href="../termos/termosdeuso.php">Termos de uso</a>
                    </div>
                </div>
            </div>

        </div>
    </footer>
</body>

<script src="script.js"></script>
</html>
<?php
require_once '../auth_admin/auth_admin.php';
include '../../funcoesPHP/connection.php';

// Pedidos (um registro por pedido) com usuário, endereço e método de pagamento
$sql = "SELECT 
            p.id_pedido,
            p.id_usuario,
            p.data_pedido,
            p.status,
            p.total,
            pa.metodo,
            u.nome AS nome_usuario,
            u.email AS email_usuario,
            e.cep AS endereco_cep,
            e.numero AS endereco_numero,
            e.complemento AS endereco_complemento
        FROM pedidos p
        JOIN usuario u ON p.id_usuario = u.id_usuario
        JOIN endereco e ON p.id_endereco_entrega = e.id_endereco
        LEFT JOIN pagamentos pa ON pa.id_pedido = p.id_pedido
        ORDER BY p.data_pedido DESC, p.id_pedido DESC";

$pedidos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Itens de todos os pedidos, agrupados por id_pedido
$sqlItens = "SELECT 
                pi.id_pedido,
                pi.quantidade,
                pi.preco_unitario,
                pr.nome AS nome_produto
             FROM pedido_itens pi
             JOIN produtos pr ON pr.id_produto = pi.id_produto";

$itensPorPedido = [];
foreach ($pdo->query($sqlItens)->fetchAll(PDO::FETCH_ASSOC) as $item) {
    $itensPorPedido[$item['id_pedido']][] = $item;
}

// Resumo para os cards do topo
$totalPedidos = count($pedidos);
$pendentes    = 0;
$faturamento  = 0;
foreach ($pedidos as $p) {
    $st = strtolower($p['status']);
    if ($st === 'pendente') {
        $pendentes++;
    }
    if ($st !== 'cancelado') {
        $faturamento += (float)$p['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos dos Clientes</title>

    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="site-header" id="siteHeader">
        <div class="header-inner">
            <a href="../inicio-admin/inicio-admin.php" class="logo-link" aria-label="Ir para o início">
                <img src="../../assetsGerais/aruana.webp" class="logo" alt="Aruanã">
            </a>

            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="mainNav">
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" id="mainNav" aria-label="Navegação principal">
                <ul class="menu-horizontal">
                    <li><a href="../pedidos/pedidos.php" class="ativo" aria-current="page"><i class="fa-solid fa-box-open"></i> Pedidos</a></li>
                    <li><a href="../produtos/produtos-admin.php"><i class="fa-solid fa-tags"></i> Produtos</a></li>
                    <li><a href="../usuarios/usuarios.php"><i class="fa-solid fa-user-group"></i> Usuários</a></li>
                </ul>
            </nav>

            <div class="profile" id="profile">
                <button type="button" class="profile-btn" id="profileBtn" aria-haspopup="true" aria-expanded="false">
                    <img src="../../assetsGerais/fotoPerfil.webp" class="foto_perfil" alt="Foto de perfil">
                    <span class="profile-text">
                        <span class="profile-name">Administrador</span>
                        <span class="profile-role">Painel Aruanã</span>
                    </span>
                    <i class="fa-solid fa-chevron-down profile-chevron"></i>
                </button>
                <div class="profile-menu" id="profileMenu" role="menu">
                    <a href="../../funcoesPHP/logout.php" role="menuitem"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container-pedidos">
        <h2>
            <i class="fa-solid fa-box-open"></i> Gerenciamento de Pedidos
            <span class="contador"><?php echo $totalPedidos; ?> <?php echo $totalPedidos === 1 ? 'pedido' : 'pedidos'; ?></span>
        </h2>

        <section class="resumo-pedidos" aria-label="Resumo dos pedidos">
            <div class="resumo-card">
                <span class="icone"><i class="fa-solid fa-receipt"></i></span>
                <div>
                    <span class="rotulo">Total de pedidos</span>
                    <span class="valor"><?php echo $totalPedidos; ?></span>
                </div>
            </div>
            <div class="resumo-card alerta">
                <span class="icone"><i class="fa-solid fa-hourglass-half"></i></span>
                <div>
                    <span class="rotulo">Pendentes</span>
                    <span class="valor"><?php echo $pendentes; ?></span>
                </div>
            </div>
            <div class="resumo-card">
                <span class="icone"><i class="fa-solid fa-sack-dollar"></i></span>
                <div>
                    <span class="rotulo">Faturamento</span>
                    <span class="valor">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></span>
                </div>
            </div>
        </section>

        <div class="tabela-wrapper">
            <table class="tabela-pedidos">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Itens</th>
                        <th>Endereço de Entrega</th>
                        <th>Data</th>
                        <th>Pagamento</th>
                        <th>Valor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($totalPedidos > 0): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td data-label="Pedido">
                                <div>#<?php echo htmlspecialchars($pedido['id_pedido']); ?></div>
                            </td>
                            <td data-label="Cliente">
                                <div>
                                    <?php echo htmlspecialchars($pedido['nome_usuario']); ?>
                                    <small><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($pedido['email_usuario']); ?></small>
                                </div>
                            </td>
                            <td data-label="Itens">
                                <div>
                                    <?php foreach ($itensPorPedido[$pedido['id_pedido']] ?? [] as $item): ?>
                                        <div class="item-linha">
                                            <?php echo htmlspecialchars($item['nome_produto']); ?>
                                            <small><?php echo (int)$item['quantidade']; ?>x R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td data-label="Endereço">
                                <div>
                                    <i class="fa-solid fa-location-dot"></i>
                                    CEP: <?php echo htmlspecialchars($pedido['endereco_cep']); ?>,
                                    Nº <?php echo htmlspecialchars($pedido['endereco_numero']); ?>
                                    <?php if (!empty($pedido['endereco_complemento'])): ?>
                                        <small><?php echo htmlspecialchars($pedido['endereco_complemento']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Data">
                                <div>
                                    <?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?>
                                    <small><?php echo date('H:i', strtotime($pedido['data_pedido'])); ?></small>
                                </div>
                            </td>
                            <td data-label="Pagamento">
                                <div><span class="metodo"><?php echo strtoupper(htmlspecialchars($pedido['metodo'] ?? 'N/A')); ?></span></div>
                            </td>
                            <td data-label="Valor">
                                <div>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></div>
                            </td>
                            <td data-label="Status">
                                <span class="status status-<?php echo htmlspecialchars(strtolower($pedido['status'])); ?>">
                                    <?php echo htmlspecialchars($pedido['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="sem-dados">
                            <i class="fa-solid fa-inbox"></i>
                            Nenhum pedido encontrado no sistema.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Comportamento do cabeçalho (sombra ao rolar, menu mobile e menu do perfil)
        (function () {
            var header = document.getElementById('siteHeader');
            var toggle = document.getElementById('menuToggle');
            var nav = document.getElementById('mainNav');
            var profileBtn = document.getElementById('profileBtn');
            var profileMenu = document.getElementById('profileMenu');

            window.addEventListener('scroll', function () {
                header.classList.toggle('scrolled', window.scrollY > 10);
            });

            toggle.addEventListener('click', function () {
                var aberto = nav.classList.toggle('open');
                toggle.setAttribute('aria-expanded', aberto);
            });

            profileBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var aberto = profileMenu.classList.toggle('open');
                profileBtn.setAttribute('aria-expanded', aberto);
            });

            document.addEventListener('click', function () {
                profileMenu.classList.remove('open');
                profileBtn.setAttribute('aria-expanded', 'false');
            });
        })();
    </script>
</body>
</html>

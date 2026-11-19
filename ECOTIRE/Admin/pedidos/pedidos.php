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
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">

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
                    <li><a href="../pedidos/pedidos.php"><i class="fa-solid fa-box-open"></i> Pedidos</a></li>
                    <li><a href="../produtos/produtos-admin.php" class="ativo" aria-current="page"><i class="fa-solid fa-tags"></i> Produtos</a></li>
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
                    <!-- Ajuste o caminho do logout conforme o seu projeto -->
                    <a href="../../funcoesPHP/logout.php" role="menuitem"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                </div>
            </div>
        </div>
    </header>

    <style>
        :root {
            --cor-primaria: rgb(43, 109, 77);
            --cor-primaria-escura: rgb(34, 86, 61);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        
        .container-pedidos {
            max-width: 1400px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .container-pedidos h2 {
            margin-top: 0;
            color: var(--cor-primaria);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .tabela-pedidos {
            width: 100%;
            border-collapse: collapse;
        }
        
        .tabela-pedidos th, .tabela-pedidos td {
            border-bottom: 1px solid #eee;
            padding: 12px 8px;
            text-align: left;
            font-size: 13px;
        }
        
        .tabela-pedidos th {
            background-color: var(--cor-primaria);
            color: white;
            font-weight: 600;
        }
        
        .tabela-pedidos th:first-child {
            border-radius: 8px 0 0 0;
        }
        
        .tabela-pedidos th:last-child {
            border-radius: 0 8px 0 0;
        }
        
        .tabela-pedidos tr:hover {
            background-color: #fcfcfc;
        }
        
        .tabela-pedidos td {
            vertical-align: top;
        }
        
        /* Status colors */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .status-pendente { background-color: #fff3cd; color: #856404; }
        .status-pago { background-color: #d4edda; color: #155724; }
        .status-enviado { background-color: #cce5ff; color: #004085; }
        .status-entregue { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelado { background-color: #f8d7da; color: #721c24; }
        
        /* Info styles */
        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        
        .info-value {
            color: #333;
        }
        
        .info-email {
            color: #666;
            font-size: 12px;
        }
        
        .info-address {
            color: #666;
            font-size: 12px;
        }
        
        .item-linha {
            margin-bottom: 6px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .tabela-pedidos {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<div class="container-pedidos">
    <h2><i class="fa-solid fa-box-open"></i> Gerenciamento de Pedidos</h2>
    
    <div style="overflow-x: auto;">
        <table class="tabela-pedidos">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Itens</th>
                    <th>Endereço de Entrega</th>
                    <th>Valor</th>
                    <th>Pagamento</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    <?php if (count($pedidos) > 0): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td>
                    <span class="info-label">#</span>
                    <span class="info-value"><?php echo htmlspecialchars($pedido['id_pedido']); ?></span>
                </td>
                <td>
                    <span class="info-label">Nome</span>
                    <span class="info-value"><?php echo htmlspecialchars($pedido['nome_usuario']); ?></span>
                    <br>
                    <span class="info-email"><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($pedido['email_usuario']); ?></span>
                </td>
                <td>
                    <span class="info-label">Produtos</span>
                    <?php foreach ($itensPorPedido[$pedido['id_pedido']] ?? [] as $item): ?>
                        <div class="item-linha">
                            <span class="info-value"><?php echo htmlspecialchars($item['nome_produto']); ?></span>
                            <br>
                            <span class="info-email">
                                <?php echo (int)$item['quantidade']; ?>x R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </td>
                <td>
                    <span class="info-address">
                        <i class="fa-solid fa-location-dot"></i> 
                        CEP: <?php echo htmlspecialchars($pedido['endereco_cep']); ?>,
                        Nº <?php echo htmlspecialchars($pedido['endereco_numero']); ?>
                        <?php if (!empty($pedido['endereco_complemento'])): ?>
                            <br>
                            <?php echo htmlspecialchars($pedido['endereco_complemento']); ?>
                        <?php endif; ?>
                    </span>
                </td>
                <td>
                    <span class="info-label">Total</span>
                    <span class="info-value" style="font-weight: bold; color: var(--cor-primaria);">
                        R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
                    </span>
                </td>
                <td>
                    <span class="info-label">Método</span>
                    <span class="info-value"><?php echo strtoupper(htmlspecialchars($pedido['metodo'] ?? 'N/A')); ?></span>
                </td>
                <td>
                    <span class="info-label">Data</span>
                    <span class="info-value"><?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?></span>
                    <br>
                    <span class="info-email"><?php echo date('H:i', strtotime($pedido['data_pedido'])); ?></span>
                </td>
                <td>
                    <span class="status status-<?php echo htmlspecialchars(strtolower($pedido['status'])); ?>">
                        <?php echo htmlspecialchars($pedido['status']); ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" sa<?php
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos dos Clientes</title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_pedidos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

<div class="container-pedidos">
    <h2><i class="fa-solid fa-box-open"></i> Gerenciamento de Pedidos</h2>
    
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
        <?php if (count($pedidos) > 0): ?>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td data-label="Pedido">
                        <div>#<?php echo htmlspecialchars($pedido['id_pedido']); ?></div>
                    </td>
                    <td data-label="Cliente">
                        <div>
                            <?php echo htmlspecialchars($pedido['nome_usuario']); ?>
                            <br>
                            <small><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($pedido['email_usuario']); ?></small>
                        </div>
                    </td>
                    <td data-label="Itens">
                        <div>
                            <?php foreach ($itensPorPedido[$pedido['id_pedido']] ?? [] as $item): ?>
                                <?php echo htmlspecialchars($item['nome_produto']); ?>
                                <small>(<?php echo (int)$item['quantidade']; ?>x R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>)</small>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td data-label="Endereço">
                        <div>
                            <i class="fa-solid fa-location-dot"></i>
                            CEP: <?php echo htmlspecialchars($pedido['endereco_cep']); ?>,
                            Nº <?php echo htmlspecialchars($pedido['endereco_numero']); ?>
                            <?php if (!empty($pedido['endereco_complemento'])): ?>
                                <br>
                                <small><?php echo htmlspecialchars($pedido['endereco_complemento']); ?></small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td data-label="Data">
                        <div>
                            <?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?>
                            <br>
                            <small><?php echo date('H:i', strtotime($pedido['data_pedido'])); ?></small>
                        </div>
                    </td>
                    <td data-label="Pagamento">
                        <div><?php echo strtoupper(htmlspecialchars($pedido['metodo'] ?? 'N/A')); ?></div>
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
                <td colspan="8" style="text-align: center; padding: 30px; color: #777;">
                    <i class="fa-solid fa-inbox fa-2x"></i><br>
                    Nenhum pedido encontrado no sistema.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>tyle="text-align: center; padding: 30px; color: #777;">
                <i class="fa-solid fa-inbox fa-2x"></i><br>
                Nenhum pedido encontrado no sistema.
            </td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

</body>
</html>
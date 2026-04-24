<?php
include '../../funcoesPHP/connection.php';

// Busca todos os pedidos cadastrados no banco de dados
// Ordenados pela data do pedido (do mais recente para o mais antigo)
$stmt = $pdo->query("SELECT * FROM pedidos ORDER BY data_pedido DESC");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container-pedidos {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .container-pedidos h2 {
            margin-top: 0;
            color: #333;
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
            padding: 15px 10px;
            text-align: left;
            font-size: 14px;
        }
        .tabela-pedidos th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
        }
        .tabela-pedidos tr:hover {
            background-color: #fcfcfc;
        }
        /* Estilos visuais para os diferentes status do ENUM */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pendente { background-color: #fff3cd; color: #856404; }
        .status-pago { background-color: #d4edda; color: #155724; }
        .status-enviado { background-color: #cce5ff; color: #004085; }
        .status-entregue { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelado { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container-pedidos">
    <h2><i class="fa-solid fa-box-open"></i> Gerenciamento de Pedidos</h2>
    
    <div style="overflow-x: auto;">
        <table class="tabela-pedidos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Usuário</th>
                    <th>ID Produto</th>
                    <th>Qtd</th>
                    <th>Data do Pedido</th>
                    <th>Método Pag.</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    <?php if (count($pedidos) > 0): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td data-label="ID Pedido">#<?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                <td data-label="ID Usuário"><?php echo htmlspecialchars($pedido['id_usuario']); ?></td>
                <td data-label="ID Produto"><?php echo htmlspecialchars($pedido['id_produto']); ?></td>
                <td data-label="Quantidade"><?php echo htmlspecialchars($pedido['quantidade']); ?></td>
                <td data-label="Data do Pedido"><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                <td data-label="Método Pag."><?php echo htmlspecialchars($pedido['metodo_pagamento'] ?: 'N/A'); ?></td>
                <td data-label="Total">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                <td data-label="Status">
                    <span class="status status-<?php echo $pedido['status']; ?>">
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
</div>

</body>
</html>
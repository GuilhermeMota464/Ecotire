<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php?erro=sessao_expirada");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Busca os pedidos do usuário ordenados por data (mais recente primeiro)
$sql = "SELECT p.*, pr.nome as nome_produto
        FROM pedidos p
        JOIN produtos pr ON p.id_produto = pr.id_produto
        WHERE p.id_usuario = ?
        ORDER BY p.data_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - Ecotire</title>
    <link rel="stylesheet" href="../carrinho/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        :root {
            --cor-primaria: rgb(43, 109, 77);
            --cor-primaria-escura: rgb(34, 86, 61);
            --fundo: rgb(241, 234, 234);
            --branco: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--fundo);
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: var(--branco);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: var(--cor-primaria);
            border-bottom: 2px solid var(--fundo);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .tabela-pedidos {
            width: 100%;
            border-collapse: collapse;
        }
        
        .tabela-pedidos th {
            background-color: var(--fundo);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--cor-primaria-escura);
        }
        
        .tabela-pedidos td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .tabela-pedidos tr:hover {
            background-color: #f9f9f9;
        }
        
        /* Status Colors */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pendente { 
            background-color: #fff3cd; 
            color: #856404; 
        }
        
        .status-pago { 
            background-color: #d4edda; 
            color: #155724; 
        }
        
        .status-enviado { 
            background-color: #cce5ff; 
            color: #004085; 
        }
        
        .status-entregue { 
            background-color: #d1e7dd; 
            color: #0f5132; 
        }
        
        .status-cancelado { 
            background-color: #f8d7da; 
            color: #721c24; 
        }
        
        .pedido-vazio {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        .pedido-vazio i {
            font-size: 50px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .btn-voltar {
            display: inline-block;
            padding: 12px 24px;
            background: var(--cor-primaria);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        
        .btn-voltar:hover {
            background: var(--cor-primaria-escura);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-box-open"></i> Meus Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="pedido-vazio">
                <i class="fa-solid fa-clipboard-list"></i>
                <p>Você ainda não fez nenhum pedido.</p>
                <a href="../inicio/index.php" class="btn-voltar">Ver Produtos</a>
            </div>
        <?php else: ?>
            <table class="tabela-pedidos">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><strong>#<?php echo $pedido['id_pedido']; ?></strong></td>
                        <td><?php echo htmlspecialchars($pedido['nome_produto']); ?></td>
                        <td><?php echo $pedido['quantidade']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                        <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                        <td>
                            <span class="status status-<?php echo $pedido['status']; ?>">
                                <?php echo htmlspecialchars($pedido['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="../inicio/index.php" class="btn-voltar">
                    <i class="fa-solid fa-plus"></i> Fazer Novo Pedido
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

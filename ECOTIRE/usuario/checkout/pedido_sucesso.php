<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$pedido_id = isset($_SESSION['ultimo_pedido']) ? $_SESSION['ultimo_pedido'] : 0;
$total_pedido = isset($_SESSION['total_pedido']) ? $_SESSION['total_pedido'] : 0;
$sucesso = isset($_SESSION['sucesso']) ? $_SESSION['sucesso'] : '';

// Limpa as variáveis de sessão do pedido
unset($_SESSION['ultimo_pedido'], $_SESSION['total_pedido'], $_SESSION['sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Realizado - Ecotire</title>
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
            max-width: 600px;
            margin: 40px auto;
            background: var(--branco);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: var(--cor-primaria);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        
        .success-icon i {
            font-size: 50px;
            color: white;
        }
        
        h1 {
            color: var(--cor-primaria);
            margin-bottom: 20px;
        }
        
        .order-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .order-info p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #333;
        }
        
        .order-info strong {
            color: var(--cor-primaria);
        }
        
        .btn-voltar {
            display: inline-block;
            padding: 15px 30px;
            background: var(--cor-primaria);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-voltar:hover {
            background: var(--cor-primaria-escura);
        }
        
        .btn-ver-pedidos {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: var(--cor-primaria);
            border: 2px solid var(--cor-primaria);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-ver-pedidos:hover {
            background: var(--cor-primaria);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fa-solid fa-check"></i>
        </div>
        
        <h1>Pedido Realizado com Sucesso!</h1>
        
        <p>Obrigado pela sua compra! Agora é só aguardar.</p>
        
        <div class="order-info">
            <p><strong>Número do Pedido:</strong> #<?php echo $pedido_id; ?></p>
            <p><strong>Total Pago:</strong> R$ <?php echo number_format($total_pedido, 2, ',', '.'); ?></p>
            <p><strong>Status:</strong> Pendente (Aguardando pagamento)</p>
        </div>
        
        <p style="color: #666; margin-bottom: 30px;">
            Você receberá um e-mail com mais informações sobre o seu pedido.
        </p>
        
        <div>
            <a href="../inicio/index.php" class="btn-voltar">
                <i class="fa-solid fa-home"></i> Voltar ao Início
            </a>
            <a href="../pedidos/meus_pedidos.php" class="btn-ver-pedidos">
                <i class="fa-solid fa-box"></i> Ver Meus Pedidos
            </a>
        </div>
    </div>
</body>
</html>

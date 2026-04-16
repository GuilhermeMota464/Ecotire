<?php
session_start();
include '../../funcoesPHP/connection.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT c.*, p.nome, p.imagem
        FROM carrinho c
        JOIN produtos p ON c.id_produto = p.id_produto
        WHERE c.id_usuario = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Meu Carrinho</title>
</head>
<body>
    <div class="container">
        <h1>Seu Carrinho</h1>
        
        <table class="tabela-carrinho">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalGeral = 0;
                foreach ($itens as $item): 
                    $subtotal = $item['preco_unitario'] * $item['quantidade'];
                    $totalGeral += $subtotal;
                ?>
                <tr>
                    <td>
                        <img src="../assets/<?php echo $item['imagem']; ?>.webp" width="50">
                        <?php echo $item['nome']; ?>
                    </td>
                    <td><?php echo $item['quantidade']; ?></td>
                    <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    <td>
                        <a href="remover.php?id=<?php echo $item['id_item']; ?>" class="btn-remover">Remover</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumo">
            <h3>Total: R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></h3>
            <button class="btn-finalizar">Finalizar Compra</button>
        </div>
    </div>
</body>
</html>
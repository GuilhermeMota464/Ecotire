<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // O operador ?? evita os "Warnings" caso o formulário não envie o campo
    $id = intval($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $valor = floatval($_POST['preco'] ?? 0);
    $promocao = intval($_POST['promo'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $estoque = intval($_POST['estoque'] ?? 0);
    $preco_promocional = isset($_POST['preco_promocional']) && $_POST['preco_promocional'] !== '' ? floatval($_POST['preco_promocional']) : null;
    $modelo = $_POST['modelo'] ?? $nome;
    $ativo = 1;


    if ($id > 0) {
        
        try {
            // 1. Usando parâmetros nomeados (:nome) em vez de (?) para não haver confusão
            $stmt = $pdo->prepare("
                UPDATE produtos
                SET nome = :nome,
                    preco_custo = :preco_custo,
                    preco_venda = :preco_venda,
                    preco_promocional = :preco_promocional,
                    modelo = :modelo,
                    estoque = :estoque,
                    imagem = :imagem,
                    ativo = :ativo
                WHERE id_produto = :id
            ");

            // 2. Colocando o array de dados DENTRO do execute() e incluindo o :id
            $sucesso = $stmt->execute([
                ':nome' => $nome,
                ':preco_custo' => $valor,
                ':preco_venda' => $valor,
                ':preco_promocional' => ($promocao ? $preco_promocional : null),
                ':modelo' => $modelo,
                ':imagem' => null,
                ':ativo' => $ativo,
                ':estoque' => $estoque,
                ':id' => $id
            ]);

            if ($sucesso) {
                echo "Produto atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o produto.";
            }
            
        } catch (PDOException $e) {
            // 3. Capturando erros corretamente no padrão PDO
            echo "Erro no banco de dados: " . $e->getMessage();
        }

    } else {
        echo "ID inválido. Certifique-se de que o formulário está enviando o 'id' do produto.";
    }
}
?>
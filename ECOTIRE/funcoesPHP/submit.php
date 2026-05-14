<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $estoque   = intval($_POST['estoque']);
    $descricao = $_POST['descricao'] ?? '';
    $promoCheck = isset($_POST['promo']);
    $preco_promocional = isset($_POST['preco_promocional']) && $_POST['preco_promocional'] !== '' ? floatval($_POST['preco_promocional']) : null;
    $modelo = $_POST['modelo'] ?? $nome;
    $ativo = 1;


    try {
        $sql = "INSERT INTO produtos (nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, imagem, ativo)
                VALUES (:nome, :preco_custo, :preco_venda, :preco_promocional, :modelo, :estoque, :imagem, :ativo)";

        $stmt = $pdo->prepare($sql);

        // imagem: o upload no admin normalmente salva em Assets e usa o nome do arquivo base.
        // Como este submit.php atual não trata upload, mantemos fallback para null.
        $imagem = null;
        // Se promo nao estiver marcado, preco_promocional vira NULL
        if (!$promoCheck) {
            $preco_promocional = null;
        }

        $valores = [
            ':nome' => $nome,
            ':preco_custo' => $preco,
            ':preco_venda' => $preco,
            ':preco_promocional' => $preco_promocional,
            ':modelo' => $modelo,
            ':estoque' => $estoque,
            ':imagem' => $imagem,
            ':ativo' => $ativo
        ];

        if ($stmt->execute($valores)) {
            echo "Produto cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o produto.";
        }

    } catch (PDOException $e) {
        echo "Erro no banco de dados: " . $e->getMessage();
    }
    $pdo = null;
    header("Location: ../../ECOTIRE/Admin/produtos/produtos-admin.php");
    exit;
}
?>
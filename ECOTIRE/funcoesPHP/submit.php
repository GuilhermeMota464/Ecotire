<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $estoque   = intval($_POST['estoque']);
    $descricao = $_POST['descricao'];

    try {
        $sql = "INSERT INTO produtos (nome, preco, estoque, descricao) VALUES (:nome, :preco, :estoque, :descricao)";

        $stmt = $pdo->prepare($sql);

        $valores = [
            ':nome' => $nome,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':descricao' => $descricao
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
<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $estoque   = intval($_POST['estoque']);
    $avaliacao = intval($_POST['avaliacao']);

    $sql = "INSERT INTO produtos (nome, preco, estoque, avaliacao) VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt) {

        $stmt = $pdo->prepare([
            ':nome' => $nome,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':avaliacao' => $avaliacao
        ]);

        if ($stmt->execute()) {
            echo "Produto cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }
        
    try{

    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
    
    $conn = null;
    }
}
?>
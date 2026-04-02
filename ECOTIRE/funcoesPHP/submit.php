<?php
include 'connection.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome      = $_POST['nome'];
    $preco     = $_POST['preco'];
    $estoque   = $_POST['estoque'];
    $avaliacao = $_POST['avaliacao'];

    $sql = "INSERT INTO produtos (nome, preco, estoque, avaliacao) VALUES (:nome, :preco, :estoque, :avaliacao)";

    try {
        //Prepara a query usando sua variável de conexão
        $stmt = $conn->prepare($sql);

        //O PDO faz o "bind" e a execução de uma vez só passando um array
        $stmt->execute([
            ':nome'      => $nome,
            ':preco'     => $preco,
            ':estoque'   => $estoque,
            ':avaliacao' => $avaliacao
        ]);

        echo "Produto cadastrado com sucesso!";

    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
    
    $conn = null;
}
?>
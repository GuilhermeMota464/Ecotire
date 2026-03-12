<?php
// 1. Include your connection file
include 'connection.php'; 
// Ensure $conn inside 'connection.php' is a MySQLi connection!

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Correct Array Syntax ([])
    $nome      = $_POST['nome'];
    $preco     = $_POST['preco'];
    $estoque   = $_POST['estoque'];
    $avaliacao = $_POST['avaliacao'];

    // 3. MySQLi uses ? placeholders (not :nome)
    $sql = "INSERT INTO produtos (nome, preco, estoque, avaliacao) VALUES (?, ?, ?, ?)";

    // 4. Use the $conn variable from your include file
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // 5. Correct Binding
        // "sdii" -> String, Double, Integer, Integer
        $stmt->bind_param("sdii", $nome, $preco, $estoque, $avaliacao);

        // 6. Execute
        if ($stmt->execute()) {
            echo "Produto cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
    
    $conn->close();
}
?>
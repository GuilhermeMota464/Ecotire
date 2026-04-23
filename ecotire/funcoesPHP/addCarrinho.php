<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../usuario/login/login.php?erro=sessao_expirada");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = $_POST['id_produto'];
    $preco = $_POST['preco'];
    $quantidade = 1;

    $sql = "INSERT INTO carrinho (id_usuario, id_produto, quantidade, preco_unitario)
            VALUES (:user, :prod, :qtd, :preco)
            ON DUPLICATE KEY UPDATE quantidade = quantidade + 1";
    $stmt = $pdo -> prepare($sql);

    try {
        $stmt->execute([
            ':user' => $id_usuario ,
            ':prod'  => $id_produto,
            ':qtd'   => $quantidade,
            ':preco' => $preco
        ]);

        http_response_code(200);
        echo "Adicionado com sucesso";
    } catch (PDOException $e){
        http_response_code(500);
        echo "Erro ao adicionar: " . $e->getMessage();
    }
}
?>
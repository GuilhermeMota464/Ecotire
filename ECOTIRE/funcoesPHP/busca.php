<?php
include 'connection.php'; 

if(isset($_GET['busca'])){
    $busca = trim($_GET['busca']);
    $sql = "SELECT * FROM produtos WHERE nome LIKE :busca LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':busca' => "%$busca%"
    ]);

    $resultados = $stmt->fetchALL(PDO::FETCH_ASSOC);

    if($resultados){
        foreach ($resultados as $produto){
            echo "<p>" .$produto['nome'] . " - R$ " . $produto['preco'] . "</p>";
        } 
    } else {
            echo "Nenhum resultado encontrado";
        }
}




?>
<?php
include 'connection.php'; 

if(isset($_GET['busca'])){
    $busca = trim($_GET['busca']);
    $sql = "SELECT * FROM produtos WHERE nome LIKE :busca LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':busca' => "%$busca%"
    ]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($resultados){
        foreach ($resultados as $produto){
            echo 
            "<a href='../pagina-produto-usuario/pagina-produto-usuario.php?produto=".urlencode($produto['nome'])."&id=".$produto['id_produto']."' 
            class='resultado-item' style='color:rgb(43, 109, 77); text-decoration:none;'>
            <div class='resultado-item'>
                ".$produto['nome']." - R$ ".$produto['preco_venda']."
            </div>
            </a>";
        } 
    } else {
            echo "Nenhum resultado encontrado";
    }
}


?>
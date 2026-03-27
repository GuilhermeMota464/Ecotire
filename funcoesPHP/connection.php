<?php
    $host = "localhost";
    $user = "root";
    $password = "Home@spSENAI2025!";
    $database = "Ecotire";
    $charset = "utf8mb4";

    $dsn = "mysql:host=$host;dbname=$database;charset=$charset";

    try {
        //Cria a conexão
        $pdo = new PDO($dsn, $user, $password);

        //Configura o PDO pra lançar exceções em caso de erro
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Conectado com sucesso!";
    } catch (PDOException $e) {
         echo "Erro na conexão" . $e->getMessage();
    };
?>
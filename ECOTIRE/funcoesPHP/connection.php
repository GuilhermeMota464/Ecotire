<?php
require_once __DIR__ . '/../../CONFIGURACAO/config.php';
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
   try {
        //Cria a conexão
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS);
        //Configura o PDO pra lançar exceções em caso de erro
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
         echo "Erro na conexão" . $e->getMessage();
    };
?>
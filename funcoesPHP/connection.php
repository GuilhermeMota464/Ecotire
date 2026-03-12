<?php
    $host = "localhost";
    $user = "root";
    $password = "Home@spSENAI2025!";
    $database = "Ecotire";

    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
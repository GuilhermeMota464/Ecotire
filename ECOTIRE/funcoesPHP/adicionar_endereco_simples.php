<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = 'Sessão expirada.';
    header("Location: ../usuario/login/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Recebe dados do formulário (regular POST)
$cep = isset($_POST['cep']) ? trim($_POST['cep']) : '';
$numero = isset($_POST['numero']) ? intval($_POST['numero']) : 0;
$complemento = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';

if (empty($cep) || $numero <= 0) {
    $_SESSION['erro'] = 'CEP e número são obrigatórios.';
    header("Location: ../usuario/carrinho/carrinho.php");
    exit;
}

// Limpa CEP (remove caracteres não numéricos)
$cep_limpo = preg_replace('/[^0-9]/', '', $cep);
if (strlen($cep_limpo) != 8) {
    $_SESSION['erro'] = 'CEP inválido. Deve ter 8 dígitos.';
    header("Location: ../usuario/carrinho/carrinho.php");
    exit;
}

// Formata CEP: 00000-000
$cep_formatado = substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3);

try {
    // Insere o endereço
    $sql = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $cep_formatado, $numero, $complemento]);
    
    $_SESSION['sucesso'] = 'Endereço adicionado com sucesso!';
    header("Location: ../usuario/carrinho/carrinho.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao adicionar endereço: ' . $e->getMessage();
    header("Location: ../usuario/carrinho/carrinho.php");
    exit;
}

<?php
session_start();
header('Content-Type: application/json');
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Recebe os dados do POST
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cep']) || empty($data['cep'])) {
    echo json_encode(['success' => false, 'message' => 'CEP é obrigatório.']);
    exit;
}

if (!isset($data['numero']) || empty($data['numero'])) {
    echo json_encode(['success' => false, 'message' => 'Número é obrigatório.']);
    exit;
}

$cep = trim($data['cep']);
$numero = intval($data['numero']);
$complemento = isset($data['complemento']) ? trim($data['complemento']) : null;

// Valida formato do CEP
$cep = preg_replace('/[^0-9]/', '', $cep);
if (strlen($cep) != 8) {
    echo json_encode(['success' => false, 'message' => 'CEP inválido. Deve ter 8 dígitos.']);
    exit;
}

// Formata CEP com hífen
$cep = substr($cep, 0, 5) . '-' . substr($cep, 5, 3);

try {
    // Insere o novo endereço
    $sql = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $cep, $numero, $complemento]);
    
    $novo_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Endereço adicionado com sucesso!',
        'id_endereco' => $novo_id,
        'cep' => $cep,
        'numero' => $numero,
        'complemento' => $complemento
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar endereço: ' . $e->getMessage()]);
}

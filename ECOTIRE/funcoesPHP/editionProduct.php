<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Método de requisição inválido.'
    ]);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
if ($modelo === '') {
    $modelo = $nome;
}

$preco_venda = floatval($_POST['preco_venda'] ?? 0);
$promocao = isset($_POST['promo']) && intval($_POST['promo']) === 1;
$preco_promocional_raw = $_POST['preco_promocional'] ?? '';
$preco_promocional = ($promocao && floatval($preco_promocional_raw) > 0)
    ? floatval($preco_promocional_raw)
    : null;

$descricao = trim($_POST['descricao'] ?? '');
$estoque = max(0, intval($_POST['estoque'] ?? 0));

if ($id <= 0) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'ID do produto inválido.'
    ]);
    exit;
}

if ($nome === '' || $preco_venda <= 0) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Preencha o nome e um preço de venda maior que zero.'
    ]);
    exit;
}

if ($promocao && ($preco_promocional === null || $preco_promocional >= $preco_venda)) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'O preço promocional deve ser menor que o preço de venda.'
    ]);
    exit;
}

// Verifica se uma nova imagem foi enviada
$novaImagemBinaria = null;
$temNovaImagem = isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE;

if ($temNovaImagem) {
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'ok' => false,
            'mensagem' => 'Falha no envio da imagem. Verifique se o arquivo não ultrapassa o limite do servidor.'
        ]);
        exit;
    }

    $tmpName = $_FILES['imagem']['tmp_name'];
    if (!is_uploaded_file($tmpName)) {
        echo json_encode([
            'ok' => false,
            'mensagem' => 'Arquivo de imagem inválido.'
        ]);
        exit;
    }

    $maxBytes = 5 * 1024 * 1024; // 5 MB
    if ($_FILES['imagem']['size'] <= 0 || $_FILES['imagem']['size'] > $maxBytes) {
        echo json_encode([
            'ok' => false,
            'mensagem' => 'A imagem deve ter no máximo 5 MB.'
        ]);
        exit;
    }

    $mimesPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mimeReal = '';

    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeReal = $finfo->file($tmpName);
    } else {
        $infoImg = @getimagesize($tmpName);
        $mimeReal = $infoImg['mime'] ?? '';
    }

    if (!in_array($mimeReal, $mimesPermitidos, true)) {
        echo json_encode([
            'ok' => false,
            'mensagem' => 'Formato não suportado. Envie JPG, PNG, WEBP ou GIF.'
        ]);
        exit;
    }

    $novaImagemBinaria = file_get_contents($tmpName);
    if ($novaImagemBinaria === false) {
        echo json_encode([
            'ok' => false,
            'mensagem' => 'Não foi possível ler o arquivo de imagem enviado.'
        ]);
        exit;
    }
}

try {
    if ($temNovaImagem) {
        $stmt = $pdo->prepare("
            UPDATE produtos
            SET nome = :nome,
                preco_venda = :preco_venda,
                preco_promocional = :preco_promocional,
                modelo = :modelo,
                descricao = :descricao,
                estoque = :estoque,
                imagem = :imagem
            WHERE id_produto = :id
        ");
        $stmt->bindValue(':imagem', $novaImagemBinaria, PDO::PARAM_LOB);
    } else {
        $stmt = $pdo->prepare("
            UPDATE produtos
            SET nome = :nome,
                preco_venda = :preco_venda,
                preco_promocional = :preco_promocional,
                modelo = :modelo,
                descricao = :descricao,
                estoque = :estoque
            WHERE id_produto = :id
        ");
    }

    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':preco_venda', $preco_venda);
    $stmt->bindValue(':preco_promocional', $preco_promocional, $preco_promocional === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':modelo', $modelo);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':estoque', $estoque, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    echo json_encode([
        'ok' => true,
        'mensagem' => 'Produto atualizado com sucesso!'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'mensagem' => 'Erro ao atualizar o produto no banco de dados.'
    ]);
}
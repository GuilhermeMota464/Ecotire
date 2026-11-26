<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Resgata os dados alinhados exatamente aos name="..." do formulário HTML
    $nome              = $_POST['nome'] ?? '';
    $modelo            = $_POST['modelo'] ?? '';
    $preco_custo       = floatval($_POST['preco_custo'] ?? 0);
    $preco_venda       = floatval($_POST['preco_venda'] ?? 0);
    $estoque           = intval($_POST['estoque'] ?? 0);
    $descricao         = $_POST['descricao'] ?? '';
    $promoCheck        = isset($_POST['promo']);
    $preco_promocional = ($promoCheck && !empty($_POST['preco_promocional'])) ? floatval($_POST['preco_promocional']) : null;
    $ativo             = 1;

    // Tratamento da imagem binária (MEDIUMBLOB)
    $imagemBinaria = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemBinaria = file_get_contents($_FILES['imagem']['tmp_name']);
    }

    try {
        // Query com a coluna descricao inclusa
        $sql = "INSERT INTO produtos (nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, imagem, descricao, ativo)
                VALUES (:nome, :preco_custo, :preco_venda, :preco_promocional, :modelo, :estoque, :imagem, :descricao, :ativo)";

        $stmt = $pdo->prepare($sql);

        // Bind explicito para garantir a gravação binária da imagem
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':preco_custo', $preco_custo);
        $stmt->bindValue(':preco_venda', $preco_venda);
        $stmt->bindValue(':preco_promocional', $preco_promocional);
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':estoque', $estoque, PDO::PARAM_INT);
        $stmt->bindValue(':imagem', $imagemBinaria, PDO::PARAM_LOB);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':ativo', $ativo, PDO::PARAM_INT);

        $stmt->execute();

    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }

    $pdo = null;
    header("Location: ../Admin/produtos/produtos-admin.php");
    exit;
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id_para_editar = $_POST['id'];
    $novo_nome = $_POST['nome'];
    $stmt_edit->close();
}

$dados_item = null;

if (isset($_GET['buscar_id'])) {
    $id_busca = $_GET['buscar_id'];

    $stmt_busca = $conn->prepare("SELECT id, nome FROM produtos WHERE id = ?");
    $stmt_busca->bind_param("i", $id_busca);
    $stmt_busca->execute();
    
    $resultado = $stmt_busca->get_result();
    
    if ($resultado->num_rows > 0) {
        $dados_item = $resultado->fetch_assoc(); 
    }
    $stmt_busca->close();
}
$conn->close();
?>
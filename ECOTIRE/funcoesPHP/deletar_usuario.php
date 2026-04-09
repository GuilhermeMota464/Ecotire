<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;

    if ($id_usuario) {
        try {
            // Prepara a query de exclusão
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            
            // Faz o bind do ID
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
            
            // Executa a exclusão
            $stmt->execute();

            // Redireciona de volta para a página de usuários
            // (Substitua 'index.php' pelo nome correto do seu arquivo principal, se for outro)
            header("Location: ../../ECOTIRE/Admin/usuarios/usuarios.php");
            exit;

        } catch (PDOException $e) {
            echo "Erro ao deletar usuário: " . $e->getMessage();
        }
    } else {
        echo "ID do usuário não fornecido para exclusão.";
    }
} else {
    header("Location:../../ECOTIRE/Admin/usuarios/usuarios.php");
    exit;
}
?>
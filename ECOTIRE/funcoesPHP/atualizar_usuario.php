<?php
include 'connection.php';

// Verifica se a requisição veio de um formulário POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e limpa os dados
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nome       = $_POST['nome'] ?? '';
    $email      = $_POST['email'] ?? '';
    $tipo       = $_POST['tipo'] ?? '';
    $senha      = $_POST['senha'] ?? '';
    
    if ($id_usuario) {
        try {
            // Verifica se o usuário digitou uma nova senha
            if (!empty($senha)) {
                // Query COM atualização de senha
                $sql = "UPDATE usuario SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id_usuario = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':senha', $senha);
                // DICA: Em sistemas reais, use password_hash($senha, PASSWORD_DEFAULT) na linha acima.
            } else {
                // Query SEM atualização de senha (mantém a antiga)
                $sql = "UPDATE usuario SET nome = :nome, email = :email, tipo = :tipo WHERE id_usuario = :id";
                $stmt = $pdo->prepare($sql);
            }

            // Faz o bind (vínculo) dos parâmetros de forma segura contra SQL Injection
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            // Executa a query
            $stmt->execute();

            // Redireciona de volta para a página de usuários após o sucesso
            // (Substitua 'index.php' pelo nome correto do seu arquivo principal, se for outro)
            header("Location: ../../ECOTIRE/Admin/usuarios/usuarios.php");
            exit;

        } catch (PDOException $e) {
            echo "Erro ao atualizar usuário: " . $e->getMessage();
        }
    } else {
        echo "ID do usuário não fornecido.";
    }
} else {
    header("Location: ../../ECOTIRE/Admin/usuarios/usuarios.php");
    exit;
}
?>

<?php
// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';

// Verifica se a requisição veio de um formulário POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Coleta os dados
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nome       = $_POST['nome'] ?? '';
    $email      = $_POST['email'] ?? '';
    $tipo       = $_POST['tipo'] ?? '';
    $senha      = $_POST['senha'] ?? '';

    if ($id_usuario) {

        try {

            // Verifica se o usuário digitou uma nova senha
            if (!empty($senha)) {

                $sql = "UPDATE usuario 
                        SET nome = :nome, email = :email, senha = :senha, tipo = :tipo 
                        WHERE id_usuario = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':senha', $senha);

            } else {

                $sql = "UPDATE usuario 
                        SET nome = :nome, email = :email, tipo = :tipo 
                        WHERE id_usuario = :id";

                $stmt = $pdo->prepare($sql);
            }

            // Vincula os parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            // Executa a atualização
            $stmt->execute();

            /*
             * ATUALIZA A SESSÃO
             *
             * Só atualiza se o usuário editado for
             * o mesmo que está atualmente logado.
             */
            if (
                isset($_SESSION['id_usuario']) &&
                (int) $_SESSION['id_usuario'] === (int) $id_usuario
            ) {

                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;

            }

            // Redireciona após o sucesso
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

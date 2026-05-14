<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../usuario/login/login.php?erro=metodo_invalido");
    exit;
}

$id_usuario_post = $_POST['id_usuario'] ?? null;

if (!$id_usuario_post) {
    header("Location: ../usuario/perfil/perfil.php?erro=id_nao_enviado");
    exit;
}

// Garante que só o usuário logado pode deletar a própria conta
if (!isset($_SESSION['id_usuario']) || (int)$_SESSION['id_usuario'] !== (int)$id_usuario_post) {
    session_destroy();
    header("Location: ../usuario/login/login.php?erro=acao_negada");
    exit;
}

try {
    $pdo->beginTransaction();

    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario_post, PDO::PARAM_INT);
    $stmt->execute();

    $pdo->commit();

    // Finaliza sessão
    session_destroy();

    // Redireciona para cadastro (página pós-exclusão)
    header("Location: ../usuario/cadastro/cadastro.php?deletado=1");
    exit;
} catch (PDOException $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header("Location: ../usuario/perfil/perfil.php?erro=deletar_falhou");
    exit;
}
?>

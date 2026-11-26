<?php
require_once __DIR__ . '/../Admin/auth_admin/auth_admin.php';
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Admin/usuarios/usuarios.php');
    exit;
}

$id_usuario_post = intval($_POST['id_usuario'] ?? 0);
$id_admin_logado = intval($_SESSION['id_usuario'] ?? $_SESSION['id'] ?? 0);

if ($id_usuario_post <= 0) {
    header('Location: ../Admin/usuarios/usuarios.php?erro=id_nao_enviado');
    exit;
}

// Impede que você exclua a própria conta de admin enquanto está logado
if ($id_admin_logado > 0 && $id_usuario_post === $id_admin_logado) {
    header('Location: ../Admin/usuarios/usuarios.php?erro=propria_conta');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario_post, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../Admin/usuarios/usuarios.php?deletado=1');
    exit;
} catch (PDOException $e) {
    header('Location: ../Admin/usuarios/usuarios.php?erro=deletar_falhou');
    exit;
}
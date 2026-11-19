
<?php

/**
 * Proteção das páginas administrativas.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica os dados da sessão.
$id_usuario = $_SESSION['id_usuario'] ?? null;

$tipo_usuario = strtolower(
    trim($_SESSION['usuario_tipo'] ?? '')
);

// Verifica se é administrador.
$eh_admin = !empty($id_usuario) &&
            $tipo_usuario === 'admin';

// Bloqueia usuários sem autorização.
if (!$eh_admin) {

    $loginFs = realpath(__DIR__ . '/../usuario/login/login.php');
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

    $loginUrl = '../../usuario/login/login.php';

    if ($loginFs && $docRoot) {

        $loginFs = str_replace('\\', '/', $loginFs);
        $docRoot = str_replace('\\', '/', $docRoot);

        if (strpos($loginFs, $docRoot) === 0) {
            $loginUrl = substr($loginFs, strlen($docRoot));
        }
    }

    header('Location: ' . $loginUrl . '?erro=acesso_negado');
    exit;
}

// Evita cache de páginas administrativas.
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>

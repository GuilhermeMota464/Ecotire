<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/connection.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/login_errors.log');

function respond(array $data, int $httpCode = 200): void
{
    http_response_code($httpCode);

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}

try {

    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    if (!$email || !is_string($email) || !$senha || !is_string($senha)) {
        respond([
            'status'  => 'error',
            'message' => 'E-mail e senha são obrigatórios.'
        ], 400);
    }

    $email = trim($email);

    $stmt = $pdo->prepare("
        SELECT
            id_usuario,
            nome,
            email,
            senha,
            tipo
        FROM usuario
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([
        ':email' => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        respond([
            'status'  => 'error',
            'message' => 'E-mail ou senha incorretos.'
        ], 401);
    }

    $senhaValida = password_verify($senha, $usuario['senha']) ||
                   hash('sha256', $senha) === $usuario['senha'] ||
                   $senha === $usuario['senha'];

    if (!$senhaValida) {
        respond([
            'status'  => 'error',
            'message' => 'E-mail ou senha incorretos.'
        ], 401);
    }

    session_regenerate_id(true);

    $_SESSION['id_usuario']    = (int) $usuario['id_usuario'];
    $_SESSION['usuario_nome']  = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo']  = $usuario['tipo'] ?? 'cliente';

    $tipoUsuario  = $usuario['tipo'] ?? 'cliente';
    $redirectUrl  = ($tipoUsuario === 'admin')
        ? '../../admin/inicio-admin/inicio-admin.php'
        : '../../usuario/inicio/index.php';

    respond([
        'status'   => 'success',
        'message'  => 'Login realizado com sucesso!',
        'redirect' => $redirectUrl
    ], 200);

} catch (PDOException $e) {

    error_log(
        '[login][DB] ' . $e->getMessage()
    );

    respond([
        'status'  => 'error',
        'message' => 'Erro interno no banco de dados.'
    ], 500);

} catch (Throwable $e) {

    error_log(
        '[login][GENERAL] ' . $e->getMessage()
    );

    respond([
        'status'  => 'error',
        'message' => 'Falha interna durante a autenticação.'
    ], 500);
}
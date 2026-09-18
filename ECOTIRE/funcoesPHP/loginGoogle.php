<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/connection.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/google_login_errors.log');

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

    // Recebe o token enviado pelo Google
    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $credential = $input['credential'] ?? null;

    if (!$credential || !is_string($credential)) {
        respond([
            'success' => false,
            'message' => 'Token do Google ausente ou inválido.'
        ], 400);
    }

    // Client ID do Google
    $clientId = '1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com';

    $client = new Google_Client([
        'client_id' => $clientId
    ]);

    // Valida o token
    $payload = $client->verifyIdToken($credential);

    if (!$payload) {
        respond([
            'success' => false,
            'message' => 'Não foi possível validar sua conta Google.'
        ], 401);
    }

    // Confirma que o Google verificou o e-mail
    if (
        !isset($payload['email_verified']) ||
        $payload['email_verified'] !== true
    ) {
        respond([
            'success' => false,
            'message' => 'O e-mail da sua conta Google não foi verificado.'
        ], 401);
    }

    $email = $payload['email'] ?? null;

    if (!$email) {
        respond([
            'success' => false,
            'message' => 'Não foi possível obter o e-mail da conta Google.'
        ], 422);
    }

    // =====================================================
    // PROCURA O USUÁRIO NO BANCO
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT
            id_usuario,
            nome,
            email,
            tipo
        FROM usuario
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([
        ':email' => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // =====================================================
    // SE NÃO EXISTIR, NÃO DEIXA ENTRAR
    // =====================================================

    if (!$usuario) {

        respond([
            'success' => false,
            'message' => 'Esta conta Google ainda não possui cadastro. Cadastre-se primeiro.'
        ], 404);
    }

    // =====================================================
    // USUÁRIO EXISTE → LOGIN
    // =====================================================

    session_regenerate_id(true);

    $_SESSION['id_usuario'] = (int) $usuario['id_usuario'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'] ?? 'cliente';

    respond([
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'redirect' => '../inicio/index.php'
    ]);

} catch (PDOException $e) {

    error_log(
        '[loginGoogle][DB] ' . $e->getMessage()
    );

    respond([
        'success' => false,
        'message' => 'Erro no banco de dados.'
    ], 500);

} catch (Throwable $e) {

    error_log(
        '[loginGoogle][GENERAL] ' . $e->getMessage()
    );

    respond([
        'success' => false,
        'message' => 'Falha na autenticação com o Google.'
    ], 500);
}
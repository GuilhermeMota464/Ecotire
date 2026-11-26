<?php

declare(strict_types=1);

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

    // Carrega as dependências
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/connection.php';

    // Inicia a sessão
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Recebe o JSON enviado pelo JavaScript
    $rawInput = file_get_contents('php://input');

    $input = json_decode($rawInput, true);

    if (!is_array($input)) {
        respond([
            'success' => false,
            'message' => 'Requisição inválida.'
        ], 400);
    }

    $credential = $input['credential'] ?? null;

    if (!$credential || !is_string($credential)) {
        respond([
            'success' => false,
            'message' => 'Token do Google ausente ou inválido.'
        ], 400);
    }

    // Client ID do Google
    $clientId = '1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com';

    // Inicializa o cliente Google
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

    // Verifica se o e-mail foi verificado pelo Google
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

    if (!$email || !is_string($email)) {
        respond([
            'success' => false,
            'message' => 'Não foi possível obter o e-mail da conta Google.'
        ], 422);
    }

    // Procura o usuário no banco de dados
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

    // Bloqueia contas que não possuem cadastro
    if (!$usuario) {
        respond([
            'success' => false,
            'message' => 'Esta conta Google ainda não possui cadastro. Cadastre-se primeiro.'
        ], 404);
    }

    // Cria a sessão do usuário
    session_regenerate_id(true);

    $_SESSION['id_usuario'] = (int) $usuario['id_usuario'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'] ?? 'cliente';

    // Login concluído
    respond([
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'redirect' => '../inicio/index.php'
    ]);

} catch (PDOException $e) {

    error_log('[loginGoogle][DB] ' . $e->getMessage());

    respond([
        'success' => false,
        'message' => 'Erro no banco de dados.'
    ], 500);

} catch (Throwable $e) {

    error_log(
        '[loginGoogle][GENERAL] ' .
        get_class($e) . ' - ' .
        $e->getMessage() .
        ' | Arquivo: ' . $e->getFile() .
        ' | Linha: ' . $e->getLine()
    );

    respond([
        'success' => false,
        'message' => 'Falha na autenticação com o Google.'
    ], 500);
}
<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/connection.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/google_cadastro_errors.log');

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

    // =====================================================
    // RECEBE O TOKEN DO GOOGLE
    // =====================================================

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


    // =====================================================
    // CLIENT ID DO GOOGLE
    // =====================================================

    $clientId = '1086800036836-iclvo8nhvs6adrm941bdi6plvj5ahjkn.apps.googleusercontent.com';

    $client = new Google_Client([
        'client_id' => $clientId
    ]);


    // =====================================================
    // VALIDA TOKEN
    // =====================================================

    $payload = $client->verifyIdToken($credential);

    if (!$payload) {

        respond([
            'success' => false,
            'message' => 'Não foi possível validar sua conta Google.'
        ], 401);
    }


    // =====================================================
    // VERIFICA E-MAIL
    // =====================================================

    if (
        !isset($payload['email_verified']) ||
        $payload['email_verified'] !== true
    ) {

        respond([
            'success' => false,
            'message' => 'O e-mail da conta Google não foi verificado.'
        ], 401);
    }


    // =====================================================
    // PEGA DADOS DO GOOGLE
    // =====================================================

    $email = $payload['email'] ?? null;
    $nome = $payload['name'] ?? null;

    if (!$email) {

        respond([
            'success' => false,
            'message' => 'Não foi possível obter o e-mail da conta Google.'
        ], 422);
    }

    if (!$nome) {
        $nome = explode('@', $email)[0];
    }


    // =====================================================
    // VERIFICA SE O USUÁRIO JÁ EXISTE
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
    // SE JÁ EXISTE, NÃO CADASTRA NOVAMENTE
    // =====================================================

    if ($usuario) {

        respond([
            'success' => false,
            'message' => 'Este e-mail já possui um cadastro. Faça login.'
        ], 409);
    }


    // =====================================================
    // CRIA SENHA ALEATÓRIA
    // =====================================================

    $senhaAleatoria = bin2hex(
        random_bytes(32)
    );

    $senhaHash = password_hash(
        $senhaAleatoria,
        PASSWORD_DEFAULT
    );


    // =====================================================
    // CRIA NOVO USUÁRIO
    // =====================================================

    $insertStmt = $pdo->prepare("
        INSERT INTO usuario
        (
            nome,
            email,
            senha,
            telefone,
            genero,
            tipo
        )
        VALUES
        (
            :nome,
            :email,
            :senha,
            :telefone,
            :genero,
            :tipo
        )
    ");

    $insertStmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':telefone' => '',
        ':genero' => 'Prefiro_nao_dizer',
        ':tipo' => 'cliente'
    ]);


    // =====================================================
    // PEGA ID DO NOVO USUÁRIO
    // =====================================================

    $usuarioId = (int) $pdo->lastInsertId();


    // =====================================================
    // CRIA SESSÃO
    // =====================================================

    session_regenerate_id(true);

    $_SESSION['id_usuario'] = $usuarioId;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_tipo'] = 'cliente';


    // =====================================================
    // CADASTRO E LOGIN REALIZADOS
    // =====================================================

    respond([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso!',
        'redirect' => '../inicio/index.php'
    ]);


} catch (PDOException $e) {

    error_log(
        '[cadastroGoogle][DB] ' . $e->getMessage()
    );

    respond([
        'success' => false,
        'message' => 'Erro no banco de dados.'
    ], 500);


} catch (Throwable $e) {

    error_log(
        '[cadastroGoogle][GENERAL] ' . $e->getMessage()
    );

    respond([
        'success' => false,
        'message' => 'Falha no cadastro com o Google.'
    ], 500);
}
<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = 'Sessão expirada. Faça login novamente.';
    header("Location: ../login/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verifica se é para adicionar um novo endereço (via form simples)
if (isset($_POST['cep']) && isset($_POST['numero'])) {
    $cep = isset($_POST['cep']) ? trim($_POST['cep']) : '';
    $numero = isset($_POST['numero']) ? intval($_POST['numero']) : 0;
    $complemento = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';

    if (empty($cep) || $numero <= 0) {
        $_SESSION['erro'] = 'CEP e número são obrigatórios.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

// Limpa CEP (remove caracteres nao numericos)
    $cep_limpo = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep_limpo) != 8) {
        $_SESSION['erro'] = 'CEP inválido. Deve ter 8 dígitos.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Formata CEP: 00000-000
    $cep_formatado = substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3);

    try {
        // Insere o endereço
        $sql = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario, $cep_formatado, $numero, $complemento]);
        
        $_SESSION['sucesso'] = 'Endereço adicionado com sucesso! Agora finalize sua compra.';
        header("Location: ../carrinho/carrinho.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao adicionar endereço: ' . $e->getMessage();
        header("Location: ../carrinho/carrinho.php");
        exit;
    }
}

// Processar cancelamento do pedido (botão Cancelar no perfil)
if (isset($_POST['cancelar_pedido']) && isset($_POST['id_pedido']) && intval($_POST['id_pedido']) > 0) {
    $id_pedido = intval($_POST['id_pedido']);

    try {
        $sql_check = "SELECT id_pedido, status FROM pedidos WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_pedido, $id_usuario]);
        $pedido = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            $_SESSION['erro'] = 'Pedido inválido.';
            header("Location: ../perfil/perfil.php");
            exit;
        }

// Se já estiver pago ou cancelado, não permite cancelar
        $status_atual = strtolower(trim($pedido['status'] ?? ''));
        if ($status_atual === 'pago' || $status_atual === 'cancelado') {

            $_SESSION['erro'] = 'Pedido já está pago e não pode ser cancelado.';
            header("Location: ../perfil/perfil.php");
            exit;
        }

        $sql_update = "UPDATE pedidos SET status = 'cancelado' WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$id_pedido, $id_usuario]);

        $_SESSION['sucesso'] = 'Pedido cancelado.';
        header("Location: ../perfil/perfil.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao cancelar pedido: ' . $e->getMessage();
        header("Location: ../perfil/perfil.php");
        exit;
    }
}

// Caso contrário, é para processar a compra (recebe dados via POST formato regular)
// Suporte ao clique no botão "Pagar Agora" do perfil.php
// Quando enviado, atualizamos o status do pedido para "pago".
if (isset($_POST['id_pedido']) && intval($_POST['id_pedido']) > 0) {

    $id_pedido = intval($_POST['id_pedido']);

    try {
        $pdo->beginTransaction();

        $sql_check = "SELECT id_pedido FROM pedidos WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_pedido, $id_usuario]);

        if (!$stmt_check->fetch()) {
            $_SESSION['erro'] = 'Pedido inválido.';
            $pdo->rollBack();
            header("Location: ../perfil/perfil.php");
            exit;
        }

        $sql_update = "UPDATE pedidos SET status = 'pago' WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$id_pedido, $id_usuario]);

        $pdo->commit();
        $_SESSION['sucesso'] = 'Pagamento confirmado!';
        header("Location: ../perfil/perfil.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['erro'] = 'Erro ao confirmar pagamento: ' . $e->getMessage();
        header("Location: ../perfil/perfil.php");
        exit;
    }
}

$id_endereco = isset($_POST['id_endereco']) ? intval($_POST['id_endereco']) : 0;
$metodo_pagamento = isset($_POST['metodo_pagamento']) ? $_POST['metodo_pagamento'] : 'pix';


// Se vier pelo botão "Pagar Agora" do perfil, já tratamos e fizemos redirect acima.
// Então, se não houver id_endereco, seguimos apenas para o fluxo normal do checkout.
if ($id_endereco <= 0) {
    $_SESSION['erro'] = 'Selecione um endereço de entrega.';
    header("Location: ../carrinho/carrinho.php");
    exit;
}


try {
    // Verifica se o carrinho tem itens
    $sql_check = "SELECT c.*, p.nome,
                          p.preco_venda,
                          p.preco_promocional
                 FROM carrinho c
                 JOIN produtos p ON c.id_produto = p.id_produto
                 WHERE c.id_usuario = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$id_usuario]);
    $itens_carrinho = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

    if (empty($itens_carrinho)) {
        $_SESSION['erro'] = 'Carrinho vazio.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Verifica se o endereço pertence ao usuário
    $sql_endereco = "SELECT id_endereco FROM endereco WHERE id_endereco = ? AND id_usuario = ?";
    $stmt_endereco = $pdo->prepare($sql_endereco);
    $stmt_endereco->execute([$id_endereco, $id_usuario]);
    if (!$stmt_endereco->fetch()) {
        $_SESSION['erro'] = 'Endereço inválido.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }

    // Inicia transação
    $pdo->beginTransaction();

    // Para cada item do carrinho, cria um pedido
    $total_geral = 0;
    $itens_inseridos = [];

    // Inicia a criação do pedido (uma linha em pedidos) e depois cria os itens.
    $sql_pedido = "INSERT INTO pedidos (id_usuario, id_endereco_entrega, total, status) VALUES (?, ?, ?, 'pendente')";
    $stmt_pedido = $pdo->prepare($sql_pedido);

    // Primeiro calcula total e armazena subtotal/preço unitário
    $itens_calculados = [];
    foreach ($itens_carrinho as $item) {
        $preco_unitario = $item['preco_promocional'] !== null ? (float)$item['preco_promocional'] : (float)$item['preco_venda'];
        $subtotal = $preco_unitario * (int)$item['quantidade'];
        $total_geral += $subtotal;
        $itens_calculados[] = [
            'id_produto' => (int)$item['id_produto'],
            'quantidade' => (int)$item['quantidade'],
            'preco_unitario' => $preco_unitario,
        ];
    }

    $stmt_pedido->execute([$id_usuario, $id_endereco, $total_geral]);
    $id_pedido_criado = $pdo->lastInsertId();

    // Insere pedido_itens
    $sql_itens = "INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_itens = $pdo->prepare($sql_itens);
    foreach ($itens_calculados as $item_calc) {
        $stmt_itens->execute([
            $id_pedido_criado,
            $item_calc['id_produto'],
            $item_calc['quantidade'],
            $item_calc['preco_unitario']
        ]);
        $itens_inseridos[] = $pdo->lastInsertId();
    }

    // Insere pagamento (1 linha em pagamentos)
    $sql_pagamento = "INSERT INTO pagamentos (id_pedido, metodo, valor, status) VALUES (?, ?, ?, 'pendente')";
    $stmt_pagamento = $pdo->prepare($sql_pagamento);
    $stmt_pagamento->execute([$id_pedido_criado, $metodo_pagamento, $total_geral]);

    // Mantém variável para compatibilidade com o restante do código
    $id_pedido_criado = (int)$id_pedido_criado;

    // Limpa o carrinho do usuário
    $sql_limpar = "DELETE FROM carrinho WHERE id_usuario = ?";
    $stmt_limpar = $pdo->prepare($sql_limpar);
    $stmt_limpar->execute([$id_usuario]);

    // Confirma a transação
    $pdo->commit();

    // Redireciona para página de sucesso
    $_SESSION['sucesso'] = 'Pedido realizado com sucesso!';
    $_SESSION['ultimo_pedido'] = $itens_inseridos[0];
    $_SESSION['total_pedido'] = $total_geral;
    
    // Redireciona para página de pedido realizado
    header("Location: pedido_sucesso.php");

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['erro'] = 'Erro ao processar pedido: ' . $e->getMessage();
    header("Location: ../carrinho/carrinho.php");
    exit;
}

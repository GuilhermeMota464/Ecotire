<?php
session_start();
include '../../funcoesPHP/connection.php';


if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = 'Sessão expirada. Faça login novamente.';
    header("Location: ../login/login.php");
    exit;
}


$id_usuario = (int) $_SESSION['id_usuario'];


// =========================================================================
// 1. ADICIONAR NOVO ENDEREÇO (via form simples)
// =========================================================================
if (isset($_POST['cep']) && isset($_POST['numero'])) {
    $cep = trim($_POST['cep']);
    $numero = intval($_POST['numero']);
    $complemento = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';


    if (empty($cep) || $numero <= 0) {
        $_SESSION['erro'] = 'CEP e número são obrigatórios.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }


    // Limpa CEP (remove caracteres não numéricos)
    $cep_limpo = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep_limpo) !== 8) {
        $_SESSION['erro'] = 'CEP inválido. Deve ter 8 dígitos.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }


    // Formata CEP: 00000-000
    $cep_formatado = substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3);


    try {
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


// =========================================================================
// 2. CANCELAR PEDIDO (botão Cancelar no perfil) + DEVOLVER ESTOQUE
// =========================================================================
if (isset($_POST['cancelar_pedido']) && isset($_POST['id_pedido']) && intval($_POST['id_pedido']) > 0) {
    $id_pedido = intval($_POST['id_pedido']);


    try {
        $pdo->beginTransaction();


        $sql_check = "SELECT id_pedido, status FROM pedidos WHERE id_pedido = ? AND id_usuario = ? FOR UPDATE";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_pedido, $id_usuario]);
        $pedido = $stmt_check->fetch(PDO::FETCH_ASSOC);


        if (!$pedido) {
            $pdo->rollBack();
            $_SESSION['erro'] = 'Pedido inválido.';
            header("Location: ../perfil/perfil.php");
            exit;
        }


        $status_atual = strtolower(trim($pedido['status'] ?? ''));
        if ($status_atual === 'pago' || $status_atual === 'cancelado') {
            $pdo->rollBack();
            $_SESSION['erro'] = 'Pedido já está pago ou cancelado e não pode ser alterado.';
            header("Location: ../perfil/perfil.php");
            exit;
        }


        // Busca os itens do pedido para devolver ao estoque
        $sql_itens_pedido = "SELECT id_produto, quantidade FROM pedido_itens WHERE id_pedido = ?";
        $stmt_itens_pedido = $pdo->prepare($sql_itens_pedido);
        $stmt_itens_pedido->execute([$id_pedido]);
        $itens_pedido = $stmt_itens_pedido->fetchAll(PDO::FETCH_ASSOC);


        $sql_devolve_estoque = "UPDATE produtos SET estoque = estoque + ? WHERE id_produto = ?";
        $stmt_devolve_estoque = $pdo->prepare($sql_devolve_estoque);


        foreach ($itens_pedido as $item_p) {
            $stmt_devolve_estoque->execute([
                (int) $item_p['quantidade'],
                (int) $item_p['id_produto']
            ]);
        }


        // Atualiza status para cancelado
        $sql_update = "UPDATE pedidos SET status = 'cancelado' WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$id_pedido, $id_usuario]);


        $pdo->commit();
        $_SESSION['sucesso'] = 'Pedido cancelado e estoque restaurado.';
        header("Location: ../perfil/perfil.php");
        exit;


    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['erro'] = 'Erro ao cancelar pedido: ' . $e->getMessage();
        header("Location: ../perfil/perfil.php");
        exit;
    }
}


// =========================================================================
// 3. PAGAR PEDIDO PENDENTE (botão "Pagar Agora" do perfil.php)
// =========================================================================
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


// =========================================================================
// 4. FINALIZAR COMPRA DO CARRINHO + BAIXA NO ESTOQUE
// =========================================================================
$id_endereco = isset($_POST['id_endereco']) ? intval($_POST['id_endereco']) : 0;
$metodo_pagamento = isset($_POST['metodo_pagamento']) ? trim($_POST['metodo_pagamento']) : 'pix';


// Garante que o método de pagamento seja válido conforme o ENUM da tabela pagamentos
$metodos_validos = ['PIX', 'CARTAO', 'BOLETO'];
$metodo_enum = strtoupper($metodo_pagamento);
if (!in_array($metodo_enum, $metodos_validos, true)) {
    $metodo_enum = 'PIX';
}


if ($id_endereco <= 0) {
    $_SESSION['erro'] = 'Selecione um endereço de entrega.';
    header("Location: ../carrinho/carrinho.php");
    exit;
}


try {
    // Inicia transação antes de ler o estoque para evitar compra simultânea da última unidade
    $pdo->beginTransaction();


    // Busca itens do carrinho + estoque atual do produto (bloqueando as linhas com FOR UPDATE)
    $sql_check = "SELECT c.id_item,
                         c.id_produto,
                         c.quantidade,
                         p.nome,
                         p.preco_venda,
                         p.preco_promocional,
                         p.estoque,
                         p.ativo
                  FROM carrinho c
                  JOIN produtos p ON c.id_produto = p.id_produto
                  WHERE c.id_usuario = ?
                  FOR UPDATE";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$id_usuario]);
    $itens_carrinho = $stmt_check->fetchAll(PDO::FETCH_ASSOC);


    if (empty($itens_carrinho)) {
        $pdo->rollBack();
        $_SESSION['erro'] = 'Carrinho vazio.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }


    // Verifica se o endereço pertence ao usuário
    $sql_endereco = "SELECT id_endereco FROM endereco WHERE id_endereco = ? AND id_usuario = ?";
    $stmt_endereco = $pdo->prepare($sql_endereco);
    $stmt_endereco->execute([$id_endereco, $id_usuario]);
    if (!$stmt_endereco->fetch()) {
        $pdo->rollBack();
        $_SESSION['erro'] = 'Endereço inválido.';
        header("Location: ../carrinho/carrinho.php");
        exit;
    }


    $total_geral = 0;
    $itens_calculados = [];


    foreach ($itens_carrinho as $item) {
        $qtd_comprada  = (int) $item['quantidade'];
        $estoque_atual = (int) $item['estoque'];
        $ativo         = (bool) $item['ativo'];


        if (!$ativo || $estoque_atual <= 0) {
            $pdo->rollBack();
            $_SESSION['erro'] = 'O produto "' . $item['nome'] . '" está esgotado.';
            header("Location: ../carrinho/carrinho.php");
            exit;
        }


        if ($qtd_comprada > $estoque_atual) {
            $pdo->rollBack();
            $_SESSION['erro'] = 'Estoque insuficiente para "' . $item['nome'] . '". Disponível: ' . $estoque_atual . '.';
            header("Location: ../carrinho/carrinho.php");
            exit;
        }


        $preco_unitario = $item['preco_promocional'] !== null
            ? (float) $item['preco_promocional']
            : (float) $item['preco_venda'];


        $subtotal = $preco_unitario * $qtd_comprada;
        $total_geral += $subtotal;


        $itens_calculados[] = [
            'id_produto'     => (int) $item['id_produto'],
            'quantidade'     => $qtd_comprada,
            'preco_unitario' => $preco_unitario,
        ];
    }


    // Insere o pedido
    $sql_pedido = "INSERT INTO pedidos (id_usuario, id_endereco_entrega, total, status) VALUES (?, ?, ?, 'pago')";
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([$id_usuario, $id_endereco, $total_geral]);
    $id_pedido_criado = (int) $pdo->lastInsertId();


    // Insere os itens do pedido e dá baixa no estoque
    $sql_itens    = "INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_itens   = $pdo->prepare($sql_itens);


    $sql_estoque  = "UPDATE produtos SET estoque = estoque - ? WHERE id_produto = ? AND estoque >= ?";
    $stmt_estoque = $pdo->prepare($sql_estoque);


    foreach ($itens_calculados as $item_calc) {
        $stmt_itens->execute([
            $id_pedido_criado,
            $item_calc['id_produto'],
            $item_calc['quantidade'],
            $item_calc['preco_unitario']
        ]);


        $stmt_estoque->execute([
            $item_calc['quantidade'],
            $item_calc['id_produto'],
            $item_calc['quantidade']
        ]);


        // Garantia extra caso o estoque tenha zerado concorrentemente
        if ($stmt_estoque->rowCount() === 0) {
            $pdo->rollBack();
            $_SESSION['erro'] = 'Um dos produtos esgotou durante a finalização da compra.';
            header("Location: ../carrinho/carrinho.php");
            exit;
        }
    }


    // Insere pagamento
    $sql_pagamento = "INSERT INTO pagamentos (id_pedido, metodo, valor, Condicao) VALUES (?, ?, ?, 'aprovado')";
    $stmt_pag = $pdo->prepare($sql_pagamento);
    $stmt_pag->execute([$id_pedido_criado, $metodo_enum, $total_geral]);


    // Limpa o carrinho do usuário
    $sql_limpar = "DELETE FROM carrinho WHERE id_usuario = ?";
    $stmt_limpar = $pdo->prepare($sql_limpar);
    $stmt_limpar->execute([$id_usuario]);


    // Confirma tudo no banco
    $pdo->commit();


    $_SESSION['sucesso'] = 'Pedido realizado com sucesso!';
    $_SESSION['ultimo_pedido'] = $id_pedido_criado;
    $_SESSION['total_pedido'] = $total_geral;


    header("Location: pedido_sucesso.php");
    exit;


} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['erro'] = 'Erro ao processar pedido: ' . $e->getMessage();
    header("Location: ../carrinho/carrinho.php");
    exit;
}
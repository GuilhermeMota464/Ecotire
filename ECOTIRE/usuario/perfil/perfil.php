<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php?erro=sessao_expirada");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Busca dados do usuário
$sql = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Busca pedidos em andamento (pendente, pago ou enviado)
$sql_pedidos_andamento = "SELECT p.*, pr.nome as nome_produto, pr.imagem as imagem_produto
        FROM pedidos p
        JOIN produtos pr ON p.id_produto = pr.id_produto
        WHERE p.id_usuario = ? AND p.status IN ('pendente', 'pago', 'enviado')
        ORDER BY p.data_pedido DESC";
$stmt = $pdo->prepare($sql_pedidos_andamento);
$stmt->execute([$id_usuario]);
$pedidos_andamento = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca pagamentos pendentes
$sql_pagamentos_pendentes = "SELECT p.*, pr.nome as nome_produto
        FROM pedidos p
        JOIN produtos pr ON p.id_produto = pr.id_produto
        WHERE p.id_usuario = ? AND p.status = 'pendente'
        ORDER BY p.data_pedido DESC";
$stmt = $pdo->prepare($sql_pagamentos_pendentes);
$stmt->execute([$id_usuario]);
$pagamentos_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca endereço do usuário
$sql_endereco = "SELECT * FROM endereco WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql_endereco);
$stmt->execute([$id_usuario]);
$enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar atualização de perfil
$msg_sucesso = '';
$msg_erro = '';

if (isset($_POST['atualizar_perfil'])) {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $genero = $_POST['genero'];
    
    $sql_update = "UPDATE usuario SET nome = ?, telefone = ?, genero = ? WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql_update);
    
    if ($stmt->execute([$nome, $telefone, $genero, $id_usuario])) {
        $msg_sucesso = 'Perfil atualizado com sucesso!';
        // Atualiza dados locais
        $usuario['nome'] = $nome;
        $usuario['telefone'] = $telefone;
        $usuario['genero'] = $genero;
    } else {
        $msg_erro = 'Erro ao atualizar perfil.';
    }
}

// Processar adição de endereço
if (isset($_POST['adicionar_endereco'])) {
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    
    $sql_insert_endereco = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql_insert_endereco);
    
    if ($stmt->execute([$id_usuario, $cep, $numero, $complemento])) {
        $msg_sucesso = 'Endereço adicionado com sucesso!';
        // Recarrega endereços
        $stmt = $pdo->prepare($sql_endereco);
        $stmt->execute([$id_usuario]);
        $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $msg_erro = 'Erro ao adicionar endereço.';
    }
}

// Processar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Ecotire</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="header">
            <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire" onclick="window.location.href='../inicio/index.php'">
            <div class="header-actions">
                <i onclick="window.location.href = '../inicio/index.php'" class="fa-solid fa-house" title="Início"></i>
                <i onclick="window.location.href = '../carrinho/carrinho.php'" class="fa-solid fa-cart-shopping" title="Meu Carrinho"></i>
                <i onclick="window.location.href = '?logout=true'" class="fa-solid fa-right-from-bracket" title="Sair"></i>
            </div>
        </div>
    </header>

    <div class="perfil-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-info">
                <i class="fa-solid fa-user-circle user-avatar"></i>
                <h3><?php echo htmlspecialchars($usuario['nome']); ?></h3>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="tab-btn active" data-tab="pedidos">
                    <i class="fa-solid fa-box-open"></i> Meus Pedidos
                </a>
                <a href="#" class="tab-btn" data-tab="pagamentos">
                    <i class="fa-solid fa-credit-card"></i> Pagamentos
                </a>
                <a href="#" class="tab-btn" data-tab="conta">
                    <i class="fa-solid fa-gear"></i> Configurações
                </a>
                <a href="#" class="tab-btn" data-tab="enderecos">
                    <i class="fa-solid fa-location-dot"></i> Endereços
                </a>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <!-- Tab: Pedidos em Andamento -->
            <div class="tab-content active" id="pedidos">
                <h2><i class="fa-solid fa-box-open"></i> Pedidos em Andamento</h2>
                
                <?php if ($msg_sucesso && isset($_POST['atualizar_perfil']) == false): ?>
                    <div class="alert sucesso"><?php echo $msg_sucesso; ?></div>
                <?php endif; ?>
                
                <?php if (empty($pedidos_andamento)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <p>Você não tem pedidos em andamento.</p>
                        <button onclick="window.location.href='../produto/produto.php'">Ver Produtos</button>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($pedidos_andamento as $pedido): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">Pedido #<?php echo $pedido['id_pedido']; ?></span>
                                <span class="order-status status-<?php echo $pedido['status']; ?>"><?php echo htmlspecialchars($pedido['status']); ?></span>
                            </div>
                            <div class="order-body">
                                <p><strong>Produto:</strong> <?php echo htmlspecialchars($pedido['nome_produto']); ?></p>
                                <p><strong>Quantidade:</strong> <?php echo $pedido['quantidade']; ?></p>
                                <p><strong>Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
                                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab: Pagamentos Pendentes -->
            <div class="tab-content" id="pagamentos">
                <h2><i class="fa-solid fa-credit-card"></i> Pagamentos Pendentes</h2>
                
                <?php if (empty($pagamentos_pendentes)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-check-circle"></i>
                        <p>Você não tem pagamentos pendentes.</p>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($pagamentos_pendentes as $pedido): ?>
                        <div class="order-card pending">
                            <div class="order-header">
                                <span class="order-id">Pedido #<?php echo $pedido['id_pedido']; ?></span>
                                <span class="order-status status-pendente">Pendente</span>
                            </div>
                            <div class="order-body">
                                <p><strong>Produto:</strong> <?php echo htmlspecialchars($pedido['nome_produto']); ?></p>
                                <p><strong>Quantidade:</strong> <?php echo $pedido['quantidade']; ?></p>
                                <p><strong>Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
                                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                                <p><strong>Método de Pagamento:</strong> <?php echo htmlspecialchars($pedido['metodo_pagamento']); ?></p>
                                <div class="actions-row">
                                    <div class="actions-item">
                                        <?php if (($pedido['status'] ?? '') === 'pago'): ?>
                                            <button type="button" class="btn-pago" disabled style="cursor:not-allowed;">
                                                <i class="fa-solid fa-check"></i> Pago
                                            </button>
                                        <?php else: ?>
                                            <form method="POST" action="../checkout/finalizar_compra.php" style="margin:0;">
                                                <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                <button type="submit" class="btn-pagar">
                                                    <i class="fa-solid fa-credit-card"></i> Pagar Agora
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <div class="actions-item">
                                        <form method="POST" action="../checkout/finalizar_compra.php" style="margin:0;">
                                            <input type="hidden" name="cancelar_pedido" value="1">
                                            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                            <?php if (($pedido['status'] ?? '') === 'pago'): ?>
                                                <button type="button" class="btn-cancelar" disabled>
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn-cancelar">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab: Configurações da Conta -->
            <div class="tab-content" id="conta">
                <h2><i class="fa-solid fa-gear"></i> Configurações da Conta</h2>
                
                <?php if ($msg_erro): ?>
                    <div class="alert erro"><?php echo $msg_erro; ?></div>
                <?php endif; ?>
                
                <?php if ($msg_sucesso && isset($_POST['atualizar_perfil'])): ?>
                    <div class="alert sucesso"><?php echo $msg_sucesso; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="form-perfil">
                    <div class="form-group">
                        <label for="nome"><i class="fa-solid fa-user"></i> Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fa-solid fa-envelope"></i> Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                        <small>Email não pode ser alterado</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone"><i class="fa-solid fa-phone"></i> Telefone</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genero"><i class="fa-solid fa-venus-mars"></i> Gênero</label>
                        <select id="genero" name="genero" required>
                            <option value="Homem" <?php echo $usuario['genero'] == 'Homem' ? 'selected' : ''; ?>>Homem</option>
                            <option value="Mulher" <?php echo $usuario['genero'] == 'Mulher' ? 'selected' : ''; ?>>Mulher</option>
                            <option value="Prefiro não dizer" <?php echo $usuario['genero'] == 'Prefiro não dizer' ? 'selected' : ''; ?>>Prefiro não dizer</option>
                            <option value="Outros" <?php echo $usuario['genero'] == 'Outros' ? 'selected' : ''; ?>>Outros</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="atualizar_perfil" class="btn-salvar">
                        <i class="fa-solid fa-check"></i> Salvar Alterações
                    </button>
                </form>
                
                <div class="danger-zone">
                    <h3><i class="fa-solid fa-triangle-exclamation"></i> Zona de Perigo</h3>
                    <p>Cuidado! Esta ação não pode ser desfeita.</p>
                    <form method="POST" action="../../funcoesPHP/deletar_usuario.php" class="delete-account-form" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível!')">
                        <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
                        <button type="submit" class="btn-danger">
                            <i class="fa-solid fa-trash"></i> Excluir Minha Conta
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tab: Endereços -->
            <div class="tab-content" id="enderecos">
                <h2><i class="fa-solid fa-location-dot"></i> Meus Endereços</h2>
                
                <div class="enderecos-grid">
                    <?php foreach ($enderecos as $endereco): ?>
                    <div class="endereco-card">
                        <i class="fa-solid fa-location-dot"></i>
                        <p><strong>CEP:</strong> <?php echo htmlspecialchars($endereco['cep']); ?></p>
                        <p><strong>Número:</strong> <?php echo $endereco['numero']; ?></p>
                        <p><strong>Complemento:</strong> <?php echo htmlspecialchars($endereco['complemento'] ?: 'Não informado'); ?></p>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Formulário para adicionar novo endereço -->
                    <div class="endereco-card add-new">
                        <i class="fa-solid fa-plus"></i>
                        <p>Adicionar Novo Endereço</p>
                        <form method="POST" class="form-endereco">
                            <input type="text" name="cep" placeholder="CEP" required>
                            <input type="number" name="numero" placeholder="Número" required>
                            <input type="text" name="complemento" placeholder="Complemento (opcional)">
                            <button type="submit" name="adicionar_endereco" class="btn-add">
                                <i class="fa-solid fa-plus"></i> Adicionar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

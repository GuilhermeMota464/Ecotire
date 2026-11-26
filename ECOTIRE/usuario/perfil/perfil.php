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

if (!$usuario) {
    session_destroy();
    header("Location: ../login/login.php?erro=sessao_expirada");
    exit;
}

// Busca pedidos em andamento (pendente, pago ou enviado)
$sql_pedidos_andamento = "SELECT p.*, pr.nome as nome_produto, pr.imagem as imagem_produto, pi.quantidade, pi.preco_unitario
        FROM pedidos p
        LEFT JOIN pedido_itens pi ON p.id_pedido = pi.id_pedido
        LEFT JOIN produtos pr ON pi.id_produto = pr.id_produto
        WHERE p.id_usuario = ? AND p.status IN ('pendente', 'pago', 'enviado')
        ORDER BY p.data_pedido DESC";
$stmt = $pdo->prepare($sql_pedidos_andamento);
$stmt->execute([$id_usuario]);
$pedidos_andamento = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca endereço do usuário
$sql_endereco = "SELECT * FROM endereco WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql_endereco);
$stmt->execute([$id_usuario]);
$enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar atualização de perfil
$msg_sucesso = '';
$msg_erro = '';
$aba_ativa = 'pedidos';

if (isset($_POST['atualizar_perfil'])) {
    $aba_ativa = 'conta';
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $digitos_telefone = preg_replace('/\D/', '', $telefone);

    if (mb_strlen($nome) < 2) {
        $msg_erro = 'Nome deve ter pelo menos 2 caracteres.';
    } elseif ($telefone !== '' && (strlen($digitos_telefone) < 10 || strlen($digitos_telefone) > 11)) {
        $msg_erro = 'Telefone inválido.';
    } else {
        $telefone_db = ($telefone !== '') ? $telefone : null;

        $sql_update = "UPDATE usuario SET nome = ?, telefone = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql_update);

        if ($stmt->execute([$nome, $telefone_db, $id_usuario])) {
            $msg_sucesso = 'Perfil atualizado com sucesso!';
            $usuario['nome'] = $nome;
            $usuario['telefone'] = $telefone_db;
            $_SESSION['usuario_nome'] = $nome;
        } else {
            $msg_erro = 'Erro ao atualizar perfil.';
        }
    }
}

// Processar adição de endereço
if (isset($_POST['adicionar_endereco'])) {
    $aba_ativa = 'enderecos';$cep = $_POST['cep'];$numero = $_POST['numero'];$complemento = $_POST['complemento'];$sql_insert_endereco = "INSERT INTO endereco (id_usuario, cep, numero, complemento) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql_insert_endereco);
    
    if ($stmt->execute([$id_usuario, $cep,$numero, $complemento])) {$msg_sucesso = 'Endereço adicionado com sucesso!';
        $stmt = $pdo->prepare($sql_endereco);
        $stmt->execute([$id_usuario]);
        $enderecos =$stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $msg_erro = 'Erro ao adicionar endereço.';
    }
}

// Processar edição de endereço
if (isset($_POST['editar_endereco'])) {$aba_ativa = 'enderecos';
    $id_endereco =$_POST['id_endereco'];
    $cep =$_POST['cep'];
    $numero =$_POST['numero'];
    $complemento =$_POST['complemento'];

    $sql_edit_endereco = "UPDATE endereco SET cep = ?, numero = ?, complemento = ? WHERE id_endereco = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql_edit_endereco);

    if ($stmt->execute([$cep,$numero, $complemento,$id_endereco, $id_usuario])) {$msg_sucesso = 'Endereço atualizado com sucesso!';
        $stmt = $pdo->prepare($sql_endereco);
        $stmt->execute([$id_usuario]);
        $enderecos =$stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $msg_erro = 'Erro ao atualizar endereço.';
    }
}

// Processar exclusão de endereço
if (isset($_POST['excluir_endereco'])) {$aba_ativa = 'enderecos';
    $id_endereco =$_POST['id_endereco'];

    try {
        $sql_del_endereco = "DELETE FROM endereco WHERE id_endereco = ? AND id_usuario = ?";
        $stmt = $pdo->prepare($sql_del_endereco);

        if ($stmt->execute([$id_endereco, $id_usuario])) {$msg_sucesso = 'Endereço excluído com sucesso!';
            $stmt = $pdo->prepare($sql_endereco);
            $stmt->execute([$id_usuario]);
            $enderecos =$stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare($sql_pedidos_andamento);
            $stmt->execute([$id_usuario]);
            $pedidos_andamento =$stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $msg_erro = 'Erro ao excluir endereço.';
        }
    } catch (PDOException $e) {$msg_erro = 'Não foi possível excluir este endereço pois ele está vinculado a um pedido.';
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
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="header">
            <img src="../../assetsGerais/aruanaCabecario.webp" class="logo" alt="Logo Aruana" onclick="window.location.href='../inicio/index.php'">
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
                <a href="#" class="tab-btn <?php echo $aba_ativa === 'pedidos' ? 'active' : ''; ?>" data-tab="pedidos">
                    <i class="fa-solid fa-box-open"></i> Meus Pedidos
                </a>
                <a href="#" class="tab-btn <?php echo $aba_ativa === 'conta' ? 'active' : ''; ?>" data-tab="conta">
                    <i class="fa-solid fa-gear"></i> Configurações
                </a>
                <a href="#" class="tab-btn <?php echo $aba_ativa === 'enderecos' ? 'active' : ''; ?>" data-tab="enderecos">
                    <i class="fa-solid fa-location-dot"></i> Endereços
                </a>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <!-- Tab: Pedidos em Andamento -->
            <div class="tab-content <?php echo $aba_ativa === 'pedidos' ? 'active' : ''; ?>" id="pedidos">
                <h2><i class="fa-solid fa-box-open"></i> Pedidos em Andamento</h2>
                
                <?php if ($msg_sucesso &&$aba_ativa === 'pedidos'): ?>
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
                        <?php foreach ($pedidos_andamento as$pedido): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">Pedido #<?php echo $pedido['id_pedido']; ?></span>
                                <span class="order-status status-<?php echo $pedido['status']; ?>"><?php echo htmlspecialchars($pedido['status']); ?></span>
                            </div>
                            <div class="order-body">
                                <p><strong>Produto:</strong> <?php echo htmlspecialchars($pedido['nome_produto']); ?></p>
                                <p><strong>Quantidade:</strong> <?php echo $pedido['quantidade']; ?></p>
                                <p><strong>Total:</strong> R$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
                                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab: Configurações da Conta -->
            <div class="tab-content <?php echo $aba_ativa === 'conta' ? 'active' : ''; ?>" id="conta">
                <h2><i class="fa-solid fa-gear"></i> Configurações da Conta</h2>
                
                <?php if ($msg_erro &&$aba_ativa === 'conta'): ?>
                    <div class="alert erro"><?php echo $msg_erro; ?></div>
                <?php endif; ?>
                
                <?php if ($msg_sucesso &&$aba_ativa === 'conta'): ?>
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
                        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>">
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
            <div class="tab-content <?php echo $aba_ativa === 'enderecos' ? 'active' : ''; ?>" id="enderecos">
                <h2><i class="fa-solid fa-location-dot"></i> Meus Endereços</h2>

                <?php if ($msg_erro &&$aba_ativa === 'enderecos'): ?>
                    <div class="alert erro"><?php echo $msg_erro; ?></div>
                <?php endif; ?>

                <?php if ($msg_sucesso &&$aba_ativa === 'enderecos'): ?>
                    <div class="alert sucesso"><?php echo $msg_sucesso; ?></div>
                <?php endif; ?>
                
                <div class="enderecos-grid">
                    <?php foreach ($enderecos as$endereco): ?>
                    <?php $idEnd = (int)$endereco['id_endereco']; ?>
                    <div class="endereco-card" id="card-end-<?php echo $idEnd; ?>">
                        <!-- Visualização padrão -->
                        <div class="endereco-view-box" id="view-end-<?php echo $idEnd; ?>">
                            <i class="fa-solid fa-location-dot"></i>
                            <p><strong>CEP:</strong> <?php echo htmlspecialchars($endereco['cep']); ?></p>
                            <p><strong>Número:</strong> <?php echo htmlspecialchars($endereco['numero']); ?></p>
                            <p><strong>Complemento:</strong> <?php echo htmlspecialchars($endereco['complemento'] ?: 'Não informado'); ?></p>

                            <div class="endereco-acoes" id="acoes-end-<?php echo $idEnd; ?>">
                                <button type="button" class="btn-endereco-editar" onclick="mostrarEdicao(<?php echo $idEnd; ?>)">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </button>
                                <button type="button" class="btn-endereco-excluir" onclick="pedirConfirmacaoExclusao(<?php echo $idEnd; ?>)">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </button>
                            </div>

                            <!-- Confirmação sem pop-up do navegador -->
                            <div class="confirmacao-exclusao" id="confirm-end-<?php echo $idEnd; ?>" style="display: none;">
                                <p>Excluir este endereço?</p>
                                <div class="endereco-acoes" style="margin-top: 8px; padding-top: 0;">
                                    <form method="POST">
                                        <input type="hidden" name="id_endereco" value="<?php echo $idEnd; ?>">
                                        <button type="submit" name="excluir_endereco" class="btn-endereco-excluir">
                                            <i class="fa-solid fa-check"></i> Sim
                                        </button>
                                    </form>
                                    <button type="button" class="btn-endereco-cancelar" onclick="cancelarConfirmacaoExclusao(<?php echo $idEnd; ?>)">
                                        Não
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Edição com o mesmo layout do card "Adicionar Novo Endereço" -->
                        <div class="endereco-edit-box" id="edit-end-<?php echo $idEnd; ?>" style="display: none;">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <p class="titulo-card-form">Editar Endereço</p>
                            <form method="POST" class="form-endereco">
                                <input type="hidden" name="id_endereco" value="<?php echo $idEnd; ?>">
                                <input type="text" name="cep" value="<?php echo htmlspecialchars($endereco['cep']); ?>" placeholder="CEP" required>
                                <input type="number" name="numero" value="<?php echo htmlspecialchars($endereco['numero']); ?>" placeholder="Número" required>
                                <input type="text" name="complemento" value="<?php echo htmlspecialchars($endereco['complemento'] ?? ''); ?>" placeholder="Complemento (opcional)">

                                <button type="submit" name="editar_endereco" class="btn-add">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </button>
                                <button type="button" class="btn-link-cancelar" onclick="cancelarEdicao(<?php echo $idEnd; ?>)">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Formulário para adicionar novo endereço -->
                    <div class="endereco-card add-new">
                        <i class="fa-solid fa-plus"></i>
                        <p class="titulo-card-form">Adicionar Novo Endereço</p>
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

    <script>
        function mostrarEdicao(id) {
            document.getElementById('card-end-' + id).classList.add('editando');
            document.getElementById('view-end-' + id).style.display = 'none';
            document.getElementById('edit-end-' + id).style.display = 'flex';
        }

        function cancelarEdicao(id) {
            document.getElementById('card-end-' + id).classList.remove('editando');
            document.getElementById('edit-end-' + id).style.display = 'none';
            document.getElementById('view-end-' + id).style.display = 'flex';
        }

        function pedirConfirmacaoExclusao(id) {
            document.getElementById('acoes-end-' + id).style.display = 'none';
            document.getElementById('confirm-end-' + id).style.display = 'block';
        }

        function cancelarConfirmacaoExclusao(id) {
            document.getElementById('confirm-end-' + id).style.display = 'none';
            document.getElementById('acoes-end-' + id).style.display = 'flex';
        }
    </script>
    <script src="script.js"></script>
</body>
</html>
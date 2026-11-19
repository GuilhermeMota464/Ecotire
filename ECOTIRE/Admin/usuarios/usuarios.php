
<?php
require_once '../auth_admin/auth_admin.php';
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_usuario, nome, email, senha, tipo FROM usuario ORDER BY id_usuario DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários - Aruanã</title>

    <link rel="stylesheet" href="style.css">

    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Ícone da aba -->
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>

<body>

<div class="main-content">

    <!-- Cabeçalho: mesmo padrão da página de produtos -->
    <header class="site-header" id="siteHeader">
        <div class="header-inner">

            <a href="../inicio-admin/inicio-admin.php" class="logo-link" aria-label="Ir para o início">
                <img src="../../assetsGerais/aruana.webp" class="logo" alt="Aruanã">
            </a>

            <button type="button" class="menu-toggle" id="menuToggle"
                aria-label="Abrir menu" aria-expanded="false" aria-controls="mainNav">
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" id="mainNav" aria-label="Navegação principal">
                <ul class="menu-horizontal">
                    <li>
                        <a href="../pedidos/pedidos.php">
                            <i class="fa-solid fa-box-open"></i> Pedidos
                        </a>
                    </li>

                    <li>
                        <a href="../produtos/produtos-admin.php">
                            <i class="fa-solid fa-tags"></i> Produtos
                        </a>
                    </li>

                    <li>
                        <a href="../usuarios/usuarios.php" class="ativo" aria-current="page">
                            <i class="fa-solid fa-user-group"></i> Usuários
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="profile" id="profile">
                <button type="button" class="profile-btn" id="profileBtn"
                    aria-haspopup="true" aria-expanded="false">

                    <img src="../../assetsGerais/fotoPerfil.webp"
                        class="foto_perfil" alt="Foto de perfil">

                    <span class="profile-text">
                        <span class="profile-name">Administrador</span>
                        <span class="profile-role">Painel Aruanã</span>
                    </span>

                    <i class="fa-solid fa-chevron-down profile-chevron"></i>
                </button>

                <div class="profile-menu" id="profileMenu" role="menu">
                    <a href="../../funcoesPHP/logout.php" role="menuitem">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </a>
                </div>
            </div>

        </div>
    </header>

    <!-- Conteúdo -->
    <div class="container">

        <div class="title-action-bar">
            <div>
                <h1>Gerenciamento de Usuários</h1>
                <p>Consulte e gerencie os usuários cadastrados no sistema.</p>
            </div>
        </div>

        <!-- Formulário de edição -->
        <section class="edit-form-container">

            <div class="form-heading">
                <h2><i class="fa-solid fa-user-pen"></i> Editar Usuário</h2>
                <p>Selecione um usuário na tabela para editar suas informações.</p>
            </div>

            <form id="editUserForm" method="post" action="../../funcoesPHP/atualizar_usuario.php">

                <div class="form-row">

                    <div class="field-container id-field">
                        <input type="text" name="id_usuario" id="edit_id"
                            placeholder=" " class="input" readonly>
                        <label for="edit_id">ID</label>
                    </div>

                    <div class="field-container">
                        <input type="text" name="nome" id="edit_nome"
                            placeholder=" " class="input" required>
                        <label for="edit_nome">NOME</label>
                    </div>

                    <div class="field-container">
                        <input type="email" name="email" id="edit_email"
                            placeholder=" " class="input" required>
                        <label for="edit_email">EMAIL</label>
                    </div>

                    <div class="field-container password-wrapper">
                        <input type="password" name="senha" id="edit_senha"
                            placeholder=" " class="input">
                        <label for="edit_senha">NOVA SENHA</label>

                        <button type="button" class="toggle-password" id="togglePassword"
                            aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="field-container">
                        <select name="tipo" id="edit_tipo" class="input">
                            <option value="cliente">CLIENTE</option>
                            <option value="admin">ADMIN</option>
                        </select>
                        <label for="edit_tipo">TIPO</label>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="save-btn">
                            <i class="fa-solid fa-floppy-disk"></i> SALVAR
                        </button>

                        <button type="button" class="cancel-btn" onclick="limparForm()">
                            <i class="fa-solid fa-eraser"></i> LIMPAR
                        </button>
                    </div>

                </div>
            </form>
        </section>

        <!-- Tabela de usuários -->
        <section class="table-section">

            <div class="table-heading">
                <div>
                    <h2>Usuários cadastrados</h2>
                    <p>Lista de contas registradas na plataforma.</p>
                </div>

                <span class="user-count">
                    <i class="fa-solid fa-users"></i>
                    <?php echo count($usuarios); ?> usuários
                </span>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Senha</th>
                            <th>Tipo</th>
                            <th>Funções</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr
                                data-id="<?php echo htmlspecialchars($usuario['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-nome="<?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-email="<?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-tipo="<?php echo htmlspecialchars($usuario['tipo'], ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                <td data-label="ID">
                                    <strong>#<?php echo htmlspecialchars($usuario['id_usuario'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                </td>

                                <td data-label="Nome">
                                    <?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td data-label="Email">
                                    <?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td data-label="Senha">
                                    <span class="password-hidden">••••••••</span>
                                </td>

                                <td data-label="Tipo">
                                    <span class="type-badge <?php echo strtolower($usuario['tipo']) === 'admin' ? 'admin' : 'cliente'; ?>">
                                        <?php echo htmlspecialchars(strtoupper($usuario['tipo']), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>

                                <td data-label="Funções" class="actions-cell">

                                    <button type="button" class="edit-btn" title="Editar usuário">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <form action="../../funcoesPHP/deletar_usuario.php"
                                        method="POST" class="delete-form"
                                        onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">

                                        <input type="hidden" name="id_usuario"
                                            value="<?php echo htmlspecialchars($usuario['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>">

                                        <button type="submit" class="delete-btn" title="Excluir usuário">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" class="empty-table">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

    </div>
</div>

<script src="script.js"></script>
</body>
</html>

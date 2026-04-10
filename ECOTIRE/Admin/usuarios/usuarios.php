<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT id_usuario, nome, email, senha, tipo FROM usuario");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC); 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ecotire</title>
    <!-- Link fonte Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Link API de icones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icone da aba no navegador -->
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>

<body>
<div class="main-content">
<!-- Cabeçalho -->
<header>
    <div class="header">
    <div class="header-top">
            <img src="../../assetsGerais/ecotire.webp" class="logo" alt="Logo Ecotire" onclick="window.location.href='admin.php'">           
            </div>

            <img src="../../assetsGerais/fotoPerfil.webp" class="foto_perfil" alt="Foto de Perfil">
    </div>
    <div class="header-bottom">
        <nav>
         <ul class="menu-horizontal">
          <li><a onclick="window.location.href='../inicio-admin/inicio-admin.php'">Pedidos</a></li>
          <li><a onclick="window.location.href='../produtos/produtos-admin.php'" >Produtos</a></li>
          <li><a onclick="window.location.href='../inicio/index.php'" style="background-color: rgb(222, 217, 217); border-radius: 5px;">Usuários</a></li>
         </ul>
        </nav>
        </div>
    </div>
</header>

<div class="main-content">
    <!-- Campo pra editar -->
    <div class="edit-form-container">
        <form id="editUserForm" method="post" action="../../funcoesPHP/atualizar_usuario.php">
            <h3><i class="fas fa-user-edit"></i> Editar Usuário</h3>
            
            <div class="form-row">
                <div class="field-container id-field">
                    <input type="text" name="id_usuario" id="edit_id" placeholder=" " class="input" readonly>
                    <label for="edit_id" style="background-color: #f1f1f1">ID</label>
                </div>
                
                <div class="field-container">
                    <input type="text" name="nome" id="edit_nome" placeholder=" " class="input" required>
                    <label for="edit_nome">NOME</label>
                </div>

                <div class="field-container">
                    <input type="email" name="email" id="edit_email" placeholder=" " class="input" required>
                    <label for="edit_email">EMAIL</label>
                </div>

                <div class="field-container password-wrapper">
                    <input type="password" name="senha" id="edit_senha" placeholder=" " class="input">
                    <label for="edit_senha">NOVA SENHA</label>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>

                <div class="field-container">
                    <select name="tipo" id="edit_tipo" class="input">
                        <option value="cliente">CLIENTE</option>
                        <option value="admin">ADMIN</option>
                    </select>
                    <label for="edit_tipo">TIPO</label>
                </div>

                <div class="button-group">
                    <button type="submit" class="save-btn">EDITAR</button>
                    <button type="button" class="cancel-btn" onclick="limparForm()">LIMPAR</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Tabela -->
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
            <tr>
                <td data-label="ID"><strong>#<?php echo $usuario['id_usuario'] ?></strong></td>
                <td data-label="NOME"><?php echo $usuario['nome'] ?></td>
                <td data-label="EMAIL"><?php echo $usuario['email'] ?></td>
                <td data-label="SENHA"><?php echo $usuario['senha'] ?></td>
                <td data-label="TIPO"><?php echo strtoupper($usuario['tipo']) ?></td>
                <td data-label="AÇÕES">
                    <button class="edit-btn" onclick="editarUsuario('<?php echo $usuario['id_usuario'] ?>', '<?php echo addslashes($usuario['nome']) ?>', '<?php echo $usuario['email'] ?>', '<?php echo $usuario['tipo'] ?>')">
                        <i class="fas fa-edit"></i>
                    </button>

                <form action="../../funcoesPHP/deletar_usuario.php" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
        <button type="submit" class="delete-btn">
            <i class="fas fa-trash"></i>
        </button>
                </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="script.js"></script>
</body>
</html>
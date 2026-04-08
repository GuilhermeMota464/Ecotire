<?php
include '../../funcoesPHP/connection.php';

$stmt = $pdo->query("SELECT * FROM usuario");
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

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Senha</th>
        <th>Admin ou humano</th>
        <th>Funções</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
    <tr>
        <td><?php echo $usuario['id_usuario'] ?></td>
        <td><?php echo $usuario['nome'] ?></td>
        <td><?php echo $usuario['email'] ?></td>
        <td><?php echo $usuario['senha'] ?></td>
        <td><?php echo $usuario['tipo'] ?></td>
        <td>
            <button class="edit-btn">Editar</button>
            <button class="delete-btn">Excluir</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
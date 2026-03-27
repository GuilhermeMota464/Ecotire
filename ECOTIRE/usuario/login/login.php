<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title>Ecotire</title>
</head>
<body>
    <form action="cadastro.php" method="post" class="card" id="cadastroForm">

    <div class="logo">
        <img src="../../assetsGerais/ecotire.webp" alt="Logo Ecotire" onclick="window.location.href='../inicio/index.php'">
    </div>

    <h2>Entre na sua conta na Ecotire</h2>
    <p>Insira suas informações.</p>

    <div class="row">
        <input name="email" id="email" type="text" placeholder="Email" required>
        <select id="domain">
            <option>@gmail.com</option>
            <option>@hotmail.com</option>
            <option>@yahoo.com</option>
            <option>@outlook.com</option>
            <option>@icloud.com</option>
            <option value="outro">Outro...</option>
        </select>
    </div>

    <div class="row">
        <input name="senha" type="password" id="senha" placeholder="Insira sua senha" required>
    </div>


    <input type="checkbox" id="terms" required>
    <label for="terms">Eu aceito os <span id="termos" onclick="window.location.href='../termos/termosdeuso.php'">termos e condições</span></label>

    <button type="submit">Entrar</button>

    <a href="../cadastro/cadastro.php">Cadastrar-se</a>

</form>

<script src="script.js"></script>

</body>
</html> 
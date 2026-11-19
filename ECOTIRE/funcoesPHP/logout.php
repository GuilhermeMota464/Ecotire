<?php
session_start(); // Inicia a sessão para poder destruí-la

// Destrói todas as variáveis de sessão
session_unset();
session_destroy();

// Manda o usuário de volta para a tela de login (?saiu=1 mostra o aviso "Você saiu da sua conta")
header("Location: ../usuario/login/login.php?saiu=1");
exit;
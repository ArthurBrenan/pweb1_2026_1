<?php
include './autenticacao.php';

session_destroy();

// Redireciona para o login
header('Location: login.php');
exit;
?>
<?php
include './autenticacao.php';

session_destroy();

// Redireciona p login
header('Location: estrutura/paginas/login.php');
exit;
?>
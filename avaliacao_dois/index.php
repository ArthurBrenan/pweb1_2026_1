<?php
include 'site/admin/header.php'; 
include 'site/admin/autenticacao.php'; 
include_once "site/admin/db.class.php";  // AGORA é db.class.php
?>

<div class="col mt-4">
    <a href="site/admin/usuario/usuarioForm.php" class="btn btn-primary">Cadastrar Usuário</a>
    <a href="site/admin/usuario/UsuarioList.php" class="btn btn-secondary">Listar Usuários</a>
</div>

<?php
include 'site/admin/footer.php';
?>
<?php
include 'site/admin/autenticacao.php'; 

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: site/admin/login.php');
    exit;
}

include 'site/admin/header.php'; 

include_once "site/admin/db.class.php";
?>

<div class="col mt-4">
    <h2>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h2>
    <hr>
    <a href="site/admin/usuario/usuarioForm.php" class="btn btn-primary">Cadastrar Usuário</a>
    <a href="site/admin/usuario/UsuarioList.php" class="btn btn-secondary">Listar Usuários</a>
    <a href="site/admin/logout.php" class="btn btn-danger">Sair</a>
</div>

<?php
include 'site/admin/footer.php';
?>
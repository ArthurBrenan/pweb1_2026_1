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
    
    <h4>📌 Usuários</h4>
    <a href="site/admin/usuario/usuarioForm.php" class="btn btn-primary">Cadastrar Usuário</a>
    <a href="site/admin/usuario/UsuarioList.php" class="btn btn-secondary">Listar Usuários</a>
    
    <hr>
    
    <h4>📰 Notícias</h4>
    <a href="site/admin/noticia/NoticiaForm.php" class="btn btn-success">Cadastrar Notícia</a>
    <a href="site/admin/noticia/NoticiaList.php" class="btn btn-secondary">Listar Notícias</a>
    
    <hr>
    
    <h4>🎟️ Ingressos</h4>
    <a href="site/admin/ingresso/IngressoForm.php" class="btn btn-warning">Cadastrar Ingresso</a>
    <a href="site/admin/ingresso/IngressoList.php" class="btn btn-secondary">Listar Ingressos</a>
    
    <hr>
    
    <h4>🎤 Artistas</h4>
    <a href="site/admin/artista/ArtistaForm.php" class="btn btn-info">Cadastrar Artista</a>
    <a href="site/admin/artista/ArtistaList.php" class="btn btn-secondary">Listar Artistas</a>
    
    <hr>
    
    <a href="site/admin/logout.php" class="btn btn-danger">Sair</a>
</div>

<?php
include 'site/admin/footer.php';
?>
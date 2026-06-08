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

<style>
    body {
        background-color: #212529 !important;
    }
</style>
<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        
        <!-- Título de boas-vindas -->
        <div class="text-center mb-5">
            <h1 style="color: #f1c40f; letter-spacing: 5px; font-size: 2rem; text-transform: uppercase; margin: 0;">
                PAINEL ADMINISTRATIVO
            </h1>
            <h2 style="color: #e0e0d1; letter-spacing: 2px; font-size: 1.2rem; margin-top: 10px;">
                Olá, <?php echo $_SESSION['usuario_nome']; ?>
            </h2>
            <div style="width: 80px; height: 2px; background: #f1c40f; margin: 15px auto;"></div>
        </div>
        
        <!-- Grid de módulos -->
        <div class="row g-4">
            
            <!-- USUÁRIOS -->
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #f1c40f; font-size: 1.3rem; letter-spacing: 2px;">USUÁRIOS</h3>
                        
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="site/admin/usuario/usuarioForm.php" class="btn btn-sm btn-warning fw-bold px-3">CADASTRAR</a>
                            <a href="site/admin/usuario/UsuarioList.php" class="btn btn-sm btn-outline-secondary px-3">LISTAR</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- NOTÍCIAS -->
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #f1c40f; font-size: 1.3rem; letter-spacing: 2px;">NOTÍCIAS</h3>
                      
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="site/admin/noticia/NoticiaForm.php" class="btn btn-sm btn-warning fw-bold px-3">CADASTRAR</a>
                            <a href="site/admin/noticia/NoticiaList.php" class="btn btn-sm btn-outline-secondary px-3">LISTAR</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- INGRESSOS -->
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #f1c40f; font-size: 1.3rem; letter-spacing: 2px;">INGRESSOS</h3>
                        
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="site/admin/ingresso/IngressoForm.php" class="btn btn-sm btn-warning fw-bold px-3">CADASTRAR</a>
                            <a href="site/admin/ingresso/IngressoList.php" class="btn btn-sm btn-outline-secondary px-3">LISTAR</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ARTISTAS -->
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #f1c40f; font-size: 1.3rem; letter-spacing: 2px;">ARTISTAS</h3>
                        
                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <a href="site/admin/artista/ArtistaForm.php" class="btn btn-sm btn-warning fw-bold px-3">CADASTRAR</a>
                            <a href="site/admin/artista/ArtistaList.php" class="btn btn-sm btn-outline-secondary px-3">LISTAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botão Sair -->
        <div class="text-center mt-5 pt-3">
            <a href="site/admin/logout.php" class="btn btn-danger px-5 py-2" style="border-radius: 30px; font-weight: bold; letter-spacing: 1px;">
                SAIR
            </a>
        </div>
        
    </div>
</div>

<?php
include 'site/admin/footer.php';
?>
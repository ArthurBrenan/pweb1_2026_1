<?php
// Caminhos 
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/autenticacao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar log
if(!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$noticiaDB = new db('noticia');
$ingressoDB = new db('ingresso');
$artistaDB = new db('artista');
$usuarioDB = new db('usuario');

// Buscar contagens
try {
    $totalNoticias = count($noticiaDB->all());
    $totalIngressos = count($ingressoDB->all());
    $totalArtistas = count($artistaDB->all());
    $totalUsuarios = count($usuarioDB->all());
} catch (Exception $e) {
    $totalNoticias = $totalIngressos = $totalArtistas = $totalUsuarios = 0;
}
?>

<!-- Container principal -->
<div style="background-color: #212529; min-height: 100vh; padding: 40px 20px;">
    <div class="container">
        
        <div class="text-center mb-4">
            <h2 style="color: #f1c40f; letter-spacing: 3px; font-size: 2rem; text-transform: uppercase;">
                PAINEL ADMINISTRATIVO
            </h2>
            <div style="width: 80px; height: 2px; background: #f1c40f; margin: 10px auto;"></div>
            <p style="color: #e0e0d1;">Bem-vindo, <?php echo isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Usuário'; ?>!</p>
        </div>
        
        <!-- Cards de estatísticas -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card text-center" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 style="color: #f1c40f;"><?php echo $totalUsuarios; ?></h3>
                        <p class="text-white">Usuários</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 style="color: #f1c40f;"><?php echo $totalNoticias; ?></h3>
                        <p class="text-white">Notícias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 style="color: #f1c40f;"><?php echo $totalIngressos; ?></h3>
                        <p class="text-white">Ingressos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                    <div class="card-body">
                        <h3 style="color: #f1c40f;"><?php echo $totalArtistas; ?></h3>
                        <p class="text-white">Artistas</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões de navegação -->
        <div class="row g-4">
            <div class="col-md-6">
                <a href="../../usuario/UsuarioList.php" class="btn btn-warning w-100 py-3" style="border-radius: 15px; font-weight: bold;">GERENCIAR USUÁRIOS</a>
            </div>
            <div class="col-md-6">
                <a href="../../noticia/NoticiaList.php" class="btn btn-warning w-100 py-3" style="border-radius: 15px; font-weight: bold;">GERENCIAR NOTÍCIAS</a>
            </div>
            <div class="col-md-6">
                <a href="../../ingresso/IngressoList.php" class="btn btn-warning w-100 py-3" style="border-radius: 15px; font-weight: bold;">GERENCIAR INGRESSOS</a>
            </div>
            <div class="col-md-6">
                <a href="../../artista/ArtistaList.php" class="btn btn-warning w-100 py-3" style="border-radius: 15px; font-weight: bold;">GERENCIAR ARTISTAS</a>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="../../logout.php" class="btn btn-danger px-5 py-2" style="border-radius: 30px;">SAIR</a>
        </div>
        
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/footer.php';
?>
<?php
// Caminho 
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois';

require_once $rootPath . '/site/admin/autenticacao.php';
require_once $rootPath . '/site/admin/db.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php';

$artistaDB = new db('artista');

// Buscar artistas
$artistas = $artistaDB->all();

// Ordenar artistas
usort($artistas, function($a, $b) {
    return strcmp($a->nome, $b->nome);
});
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diversa - Memória Musical</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-banner {
            position: relative;
            background: linear-gradient(145deg, #f1c40f, #d4a00a);
            border-radius: 30px;
            overflow: hidden;
            margin: 30px auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 30px rgba(241,196,15,0.2);
        }
        
        .hero-banner::before {
            content: "♪";
            position: absolute;
            font-size: 300px;
            opacity: 0.1;
            bottom: -80px;
            right: -50px;
            font-family: monospace;
            color: #000;
        }
        
        .hero-banner::after {
            content: "♫";
            position: absolute;
            font-size: 200px;
            opacity: 0.08;
            top: -50px;
            left: -30px;
            font-family: monospace;
            color: #000;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 40px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 900;
            color: #1a1a1a;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 2px;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            color: #2c2c2c;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .btn-artistas {
            background-color: #1a1a1a;
            color: #f1c40f;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-artistas:hover {
            background-color: #000;
            color: #ffd700;
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        
        /* Cards */
        .artist-card {
            background: linear-gradient(145deg, #1e1e1e, #161616);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid #2c2c2c;
            height: 100%;
            cursor: pointer;
        }
        
        .artist-card:hover {
            transform: translateY(-10px);
            border-color: #f1c40f;
            box-shadow: 0 15px 40px rgba(241,196,15,0.2);
        }
        
        .artist-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        
        .artist-card:hover .artist-img {
            transform: scale(1.05);
        }
        
        .img-container {
            overflow: hidden;
            position: relative;
        }
        
        .img-container::after {
            content: "🎸";
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 30px;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-shadow: 0 0 5px rgba(0,0,0,0.5);
        }
        
        .artist-card:hover .img-container::after {
            opacity: 0.7;
        }
        
        .artist-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #f1c40f;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .artist-description {
            font-size: 0.85rem;
            color: #b0b0b0;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-content {
                padding: 30px 20px;
            }
            
            .artist-img {
                height: 220px;
            }
        }
        
        @media (max-width: 992px) {
            .row-cols-md-4 > .col {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (max-width: 576px) {
            .row-cols-md-4 > .col {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="hero-banner">
            <div class="hero-content text-center">
                <h1 class="hero-title">O SHOW NÃO PODE PARAR</h1>
                <h2 class="hero-title" style="font-size: 1.8rem; margin-top: -10px;">(MAS ELES PARARAM)</h2>
                
                
                <!-- Botão Listagem Artistas -->
                <div class="mt-4">
                    <a href="../../artista/ArtistaList.php" class="btn-artistas">
                        <i class="fas fa-list"></i>
                        LISTAGEM DE ARTISTAS
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Artistas-->
    <main class="container my-5">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if(!empty($artistas)): ?>
                <?php foreach($artistas as $artista): ?>
                <div class="col">
                    <div class="artist-card">
                        <div class="img-container">
                            <?php if(!empty($artista->imagem)): ?>
                                <img src="/pweb1_2026_1/avaliacao_dois/site/admin/uploads/<?php echo $artista->imagem; ?>" 
                                     class="artist-img" 
                                     alt="<?php echo htmlspecialchars($artista->nome); ?>">
                            <?php else: ?>
                                <img src="../img/sem-imagem.jpg" 
                                     class="artist-img" 
                                     alt="Sem imagem">
                            <?php endif; ?>
                        </div>
                        <div class="p-3">
                            <div class="artist-name">
                                <?php echo strtoupper(htmlspecialchars($artista->nome)); ?>
                            </div>
                            <div class="artist-description">
                                <?php 
                                $descricao = htmlspecialchars($artista->descricao);
                                echo nl2br(substr($descricao, 0, 120));
                                if(strlen($descricao) > 120) echo '...';
                                ?>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <?php echo !empty($artista->ano_morte) ? 'Partiu em: ' . $artista->ano_morte : 'Lenda Eterna'; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5 rounded-3" style="background: rgba(241,196,15,0.1);">
                        <i class="fas fa-drumstick-bite" style="font-size: 60px; color: #f1c40f; margin-bottom: 20px;"></i>
                        <h3 class="text-white">Nenhum artista cadastrado no momento.</h3>
                        <p class="text-white-50">Volte em breve para conhecer nossa coletânea de talentos que partiram.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        
    </main>

    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php


// Incluir arquivos necessários (caminho absoluto a partir da raiz do projeto)
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/autenticacao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

// Instanciar as classes
$noticiaDB = new db('noticia');
$ingressoDB = new db('ingresso');
$artistaDB = new db('artista');

// Buscar últimas 3 notícias
$ultimasNoticias = $noticiaDB->all();
$ultimasNoticias = array_slice($ultimasNoticias, 0, 4);

// Buscar todos os ingressos disponíveis
$ingressos = $ingressoDB->all();

// Buscar artistas
$artistas = $artistaDB->all();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post Mortem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../style.css">

    <style>
    .lineup-container {
        color: #fff;
        padding: 50px 20px;
        text-align: center;
        font-family: 'sans-serif', serif;
        text-transform: uppercase;
    }
    .tier {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    .tier-1 h1 {
        font-size: clamp(3rem, 10vw, 6rem);
        margin: 0;
        color: #f1c40f;
    }
    .tier-2 h2 {
        font-size: clamp(1.5rem, 6vw, 3rem);
        margin: 0;
    }
    .tier-3 h3 {
        font-size: clamp(1rem, 4vw, 1.8rem);
        font-weight: normal;
        color: #ccc;
    }
    .tier-4 {
        max-width: 800px;
        margin: 0 auto;
        gap: 10px;
    }
    .tier-4 span {
        font-size: 0.9rem;
        color: #888;
    }
    .tier-4 span:not(:last-child)::after {
        content: "|";
        margin-left: 10px;
        color: #444;
    }
</style>
</head>

<body style="background-color: #212529;">
    

    <main>
        <!-- Carrossel com imagens fixas (caminho corrigido) -->
        <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/capa.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/29fev.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/30fev.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/31fev.png" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

      <!-- Line-up dinâmico vindo do banco -->
<section class="lineup-container">
    <?php if(!empty($artistas)): ?>
        <?php 
        $totalArtistas = count($artistas);
        $nomes = array_map(function($a) {
            return strtoupper(htmlspecialchars($a->nome));
        }, $artistas);
        
        // Distribuição: 1 no tier1, 2 no tier2, 4 no tier3
        $tier1 = array_slice($nomes, 0, 1);      // 1 artista
        $tier2 = array_slice($nomes, 1, 2);      // 2 artistas
        $tier3 = array_slice($nomes, 3, 4);      // 4 artistas
        $tier4 = array_slice($nomes, 7);         // restante
        ?>
        
        <!-- Tier 1 - Headliner -->
        <?php if(!empty($tier1)): ?>
        <div class="tier tier-1">
            <h1><?php echo implode(' &bull; ', $tier1); ?></h1>
        </div>
        <?php endif; ?>
        
        <!-- Tier 2 -->
        <?php if(!empty($tier2)): ?>
        <div class="tier tier-2">
            <h2><?php echo implode(' &bull; ', $tier2); ?></h2>
        </div>
        <?php endif; ?>
        
        <!-- Tier 3 -->
        <?php if(!empty($tier3)): ?>
        <div class="tier tier-3">
            <h3><?php echo implode(' &bull; ', $tier3); ?></h3>
        </div>
        <?php endif; ?>
        
        <!-- Tier 4 - Restante dos artistas -->
        <?php if(!empty($tier4)): ?>
        <div class="tier tier-4">
            <?php foreach($tier4 as $artista): ?>
                <span><?php echo $artista; ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Fallback caso não tenha artistas no banco -->
        <div class="tier tier-1">
            <h1>CHICO BUARQUE</h1>
        </div>
        <div class="tier tier-2">
            <h2>RITA LEE &bull; THE BEATLES</h2>
        </div>
        <div class="tier tier-3">
            <h3>TIM MAIA &bull; ELIS REGINA &bull; BEETHOVEN &bull; AMY WINEHOUSE</h3>
        </div>
        <div class="tier tier-4">
            <span>MARÍLIA MENDONÇA</span>
            <span>FREDDIE MERCURY</span>
            <span>TOM JOBIM</span>
            <span>OZZY OSBOURNE</span>
            <span>GAL COSTA</span>
            <span>KURT COBAIN</span>
        </div>
    <?php endif; ?>
</section>
</section>

        <div class="container-fluid px-4">
            <div class="row gap-5 justify-content-center">

                <!-- Coluna de Ingressos -->
                <main class="col-md-6 p-4 text-white shadow-lg"
                    style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333;">

                    <section class="countdown-container text-center mb-5 p-4 rounded-4"
                        style="background: linear-gradient(145deg, #1e1e1e, #252525); border: 1px solid #444;">
                        <h4 class="text-uppercase mb-4" style="letter-spacing: 3px; color: #f1c40f; font-weight: bold;">
                            O Portal abre em:</h4>
                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <div class="time-unit">
                                <span id="days" class="d-block h1 fw-bold text-white">00</span>
                                <small class="text-warning">DIAS</small>
                            </div>
                            <div class="h1 text-secondary">:</div>
                            <div class="time-unit">
                                <span id="hours" class="d-block h1 fw-bold text-white">00</span>
                                <small class="text-warning">HORAS</small>
                            </div>
                            <div class="h1 text-secondary">:</div>
                            <div class="time-unit">
                                <span id="minutes" class="d-block h1 fw-bold text-white">00</span>
                                <small class="text-warning">MIN</small>
                            </div>
                        </div>
                    </section>

                    <section class="gallery-container">
                        <h3 class="mb-4 text-center fw-light" style="letter-spacing: 2px;">INGRESSOS</h3>

                        <?php if(!empty($ingressos)): ?>
                            <?php foreach($ingressos as $ingresso): ?>
                            <div class="card mb-4 border-0 shadow-sm" style="background: #252525; border-radius: 15px;">
                                <div class="card-body">
                                    <h6 class="card-title text-warning fw-bold"><?php echo strtoupper(htmlspecialchars($ingresso->nome)); ?></h6>
                                    <p class="card-text small text-secondary" style="line-height: 1.4;">
                                        <?php echo htmlspecialchars($ingresso->descricao); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-warning fw-bold">R$ <?php echo number_format($ingresso->valor, 2, ',', '.'); ?></span>
                                        <a class="btn btn-sm btn-outline-warning rounded-pill px-3" href="#"
                                            style="font-size: 0.7rem;">Comprar</a>
                                    </div>
                                    <small class="text-muted"><?php echo $ingresso->quantidade; ?> disponíveis</small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="card mb-4 border-0 shadow-sm" style="background: #252525; border-radius: 15px;">
                                <div class="card-body">
                                    <p class="card-title text-warning fw-bold text-center">PASSAGEM DE IDA</p>
                                    <p class="card-text small text-secondary" style="line-height: 1.4;">
                                        Para quem só quer dar uma espiadinha no além sem se comprometer com a eternidade.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-warning fw-bold">R$ 199,90</span>
                                        <a class="btn btn-sm btn-outline-warning rounded-pill px-3" href="#">Comprar</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                </main>

                <!-- Coluna de Notícias -->
                <aside class="col-md-4 text-light p-4 shadow-lg"
                    style="border-radius: 20px; background-color: #1a1a1a; border: 1px solid #333;">

                    <h5 class="mb-4 text-uppercase fw-bold"
                        style="color:white; font-size: 0.8rem; letter-spacing: 2px; border-bottom: 1px solid #333; padding-bottom: 10px;">
                        COMUNICADOS
                    </h5>

                    <?php if(!empty($ultimasNoticias)): ?>
                        <?php foreach($ultimasNoticias as $noticia): ?>
                        <div class="card mb-4 border-0 shadow-sm" style="background: #252525; border-radius: 15px;">
                            <div class="card-body">
                                <h6 class="card-title text-warning fw-bold"><?php echo strtoupper(htmlspecialchars($noticia->titulo)); ?></h6>
                                <p class="card-text small text-secondary" style="line-height: 1.4;">
                                    <?php echo htmlspecialchars(substr($noticia->resumo, 0, 150)); ?>...
                                </p>
                                <a class="btn btn-sm btn-outline-warning rounded-pill px-3" href="#">Ler mais</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card mb-4 border-0 shadow-sm" style="background: #252525; border-radius: 15px;">
                            <div class="card-body">
                                <h6 class="card-title text-warning fw-bold">MENOS SANGUE, MAIS COMPROMISSO</h6>
                                <p class="card-text small text-secondary">A edição deste ano não exige mais o pacto de sangue para o VIP.</p>
                                <a class="btn btn-sm btn-outline-warning rounded-pill px-3" href="#">Ler mais</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>

        <!-- Patrocinadores com imagens fixas (caminho corrigido) -->
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <h5 style="color: #e0e0d1; text-align: center;">PATROCINADORES OURO</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/funeraria wolf.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/dal bosco.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/funeralagency.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/asw.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/passaro.png" alt="marcas">
                </div>

                <h5 style="color: #e0e0d1; text-align: center;">PATROCINADORES PRATA</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/it.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/lucifer.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/logoTGP.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/eljardineiro.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/walkingdeade.png" alt="marcas">
                </div>

                <h5 style="color: #e0e0d1; text-align: center;">PATROCINADORES BRONZE</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/mc.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/onu.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/dunkindunets.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/pizzahut.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/friboi.png" alt="marcas">
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="blog-footer" style="text-align: center;">
        <div class="row">
            <div class="col p-0" style="background-color: #e0e0d1;">
                <a href="#" style="text-decoration: none; color: rgb(0, 0, 0); display: block; padding: 15px 0;">Apenas
                    para almas com mais de 18 anos de experiência terrena</a>
            </div>
        </div>
    </footer>

    <footer class="blog-footer" style="text-align: center;">
        <div class="row">
            <div class="col-3 p-0" style="background-color: rgb(85, 84, 83);">
                <a href="#" style="text-decoration: none; color: white; display: block; padding: 15px 0;">@instagrampostmortem</a>
            </div>
            <div class="col-3 p-0" style="background-color: #f1c40f;">
                <a href="#" style="text-decoration: none; color: white; display: block; padding: 15px 0;">@facebookpostmortem</a>
            </div>
            <div class="col-3 p-0" style="background-color: rgb(85, 84, 83);">
                <a href="#" style="text-decoration: none; color: white; display: block; padding: 15px 0;">@youtubepostmortem</a>
            </div>
            <div class="col-3 p-0" style="background-color: #f1c40f;">
                <a href="#" style="text-decoration: none; color: white; display: block; padding: 15px 0;">@threadspostmortem</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Caminho absoluto usando __DIR__
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois';

require_once $rootPath . '/site/admin/autenticacao.php';
require_once $rootPath . '/site/admin/db.class.php';

// Instanciar a classe para artista
$artistaDB = new db('artista');

// Buscar todos os artistas
$artistas = $artistaDB->all();

// Ordenar artistas por nome
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
    <link href="../style.css" rel="stylesheet">
</head>

<body style="background-color: #212529;">

    <div class="row d-flex justify-content-center align-items-center rounded bg-dark p-3 text-white shadow-lg">

        <div class="col-3">
            <header class="d-flex rounded justify-content-left">
                <a class="text-decoration-none" href="index.php"
                    style="font-family: sans-serif; font-size: 2rem; color: #e0e0d1; letter-spacing: 0.2em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">POST
                    MORTEM
                </a>
            </header>
        </div>

        <div class="col-3"></div>
        <div class="col-6">
            <nav class="navbar d-flex justify-content-between rounded">
                <div class="nav-scroller d-flex justify-content-around w-100"
                    style="font-family: sans-serif; font-size: 1rem; color: #e0e0d1; letter-spacing: 0.1em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">
                    <a class="text-decoration-none" href="index.php" style="color: #e0e0d1;">PÁGINA INICIAL</a>
                    <a class="text-decoration-none" href="sobre.php" style="color: #e0e0d1;">SOBRE NÓS</a>
                    <a class="text-decoration-none" href="contato.php" style="color: #e0e0d1;">CONTATO</a>
                    <a class="text-decoration-none" href="diversa.php" style="color: #e0e0d1;">DIVERSA</a>
                </div>
            </nav>
        </div>
    </div>

    <section class="container my-5 text-center px-4">
        <div class="p-4 rounded-3 border border-warning" style="background: rgba(255, 193, 7, 0.1);">
            <h2 class="text-warning fw-bold mb-3">O SHOW NÃO PODE PARAR (MAS ELES PARARAM)</h2>
            <p class="fs-5 text-light opacity-75 mx-auto" style="max-width: 900px;">
                Uma coletânea de gênios que já bateram as botas, mas continuam rendendo royalties.
                De overdoses clássicas a tragédias inacreditáveis, aqui a gente celebra quem virou
                lenda... e quem virou estatística. Bora relembrar quem partiu dessa para uma melhor, ou pelo menos para uma onde não se
                paga imposto de renda.
            </p>
        </div>
    </section>

    <main class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if(!empty($artistas)): ?>
                <?php foreach($artistas as $artista): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if(!empty($artista->imagem)): ?>
                            <img src="/pweb1_2026_1/avaliacao_dois/site/admin/uploads/<?php echo $artista->imagem; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($artista->nome); ?>">
                        <?php else: ?>
                            <img src="../img/sem-imagem.jpg" class="card-img-top" alt="Sem imagem">
                        <?php endif; ?>
                        <div class="card-body bg-dark text-white">
                            <h5 class="card-title"><?php echo strtoupper(htmlspecialchars($artista->nome)); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($artista->descricao)); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-white">
                    <p>Nenhum artista cadastrado no momento.</p>
                    <p>Volte em breve para conhecer nossa coletânea de talentos que partiram.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

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
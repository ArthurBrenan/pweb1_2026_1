<?php
session_start();

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../admin/login.php');
    exit;
}

// PROCESSAR COMPRA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar_ingresso']) && isset($_POST['ingresso_id'])) {
    $id = (int)$_POST['ingresso_id'];
    $quantidade = (int)$_POST['quantidade'];
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';
    
    try {
        $dsn = 'mysql:host=localhost;dbname=av2;charset=utf8';
        $pdo = new PDO($dsn, 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT quantidade FROM ingresso WHERE id = ?");
        $stmt->execute([$id]);
        $ingresso = $stmt->fetch(PDO::FETCH_OBJ);
        
        $mensagemCompra = '';
        $tipoMensagem = '';
        
        if ($ingresso) {
            if ($ingresso->quantidade >= $quantidade && $quantidade > 0) {
                $novoEstoque = $ingresso->quantidade - $quantidade;
                $stmt = $pdo->prepare("UPDATE ingresso SET quantidade = ? WHERE id = ?");
                $resultado = $stmt->execute([$novoEstoque, $id]);
                
                if ($resultado) {
                    $mensagemCompra = "Compra realizada com sucesso! Voce comprou $quantidade ingresso(s).";
                    $tipoMensagem = "success";
                } else {
                    $mensagemCompra = "Erro ao processar a compra. Tente novamente.";
                    $tipoMensagem = "error";
                }
            } else {
                $mensagemCompra = "Estoque insuficiente! Disponivel: " . $ingresso->quantidade . " ingressos.";
                $tipoMensagem = "error";
            }
        } else {
            $mensagemCompra = "Ingresso nao encontrado!";
            $tipoMensagem = "error";
        }
    } catch (PDOException $e) {
        $mensagemCompra = "Erro no banco de dados: " . $e->getMessage();
        $tipoMensagem = "error";
    }
}

// Incluir arquivos necessarios
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/autenticacao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

// Instanciar as classes
$noticiaDB = new db('noticia');
$ingressoDB = new db('ingresso');
$artistaDB = new db('artista');

// Buscar ultimas noticias
$ultimasNoticias = $noticiaDB->all();
$ultimasNoticias = array_slice($ultimasNoticias, 0, 4);

// Buscar todos os ingressos disponiveis
$ingressos = $ingressoDB->all();

// Buscar artistas
$artistas = $artistaDB->all();

// Configurar a data do evento para contagem regressiva
$dataEvento = '2026-10-31 20:00:00';
$dataEventoTimestamp = strtotime($dataEvento);
$agora = time();
$diferenca = $dataEventoTimestamp - $agora;

$diasRestantes = floor($diferenca / (60 * 60 * 24));
$horasRestantes = floor(($diferenca % (60 * 60 * 24)) / (60 * 60));
$minutosRestantes = floor(($diferenca % (60 * 60)) / 60);

if ($diferenca < 0) {
    $diasRestantes = 0;
    $horasRestantes = 0;
    $minutosRestantes = 0;
}
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post Mortem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../style.css">
    <style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* Estilo original do line-up */
    .lineup-container {
        color: #fff;
        padding: 50px 20px;
        text-align: center;
        font-family: 'sans-serif', serif;
        text-transform: uppercase;
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        margin: 30px 0;
        border: 1px solid #2c2c2c;
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
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 1px solid #333;
        padding-bottom: 10px;
    }
    
    .section-header h3, .section-header h5 {
        margin-bottom: 0;
        letter-spacing: 2px;
        color: #f1c40f;
        font-size: 1.2rem;
        text-transform: uppercase;
    }
    
    .btn-ver-todos {
        background-color: transparent;
        border: 1px solid #f1c40f;
        color: #f1c40f;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.7rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-ver-todos:hover {
        background-color: #f1c40f;
        color: #000;
    }
    
    .custom-card {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 20px;
        border: 1px solid #2c2c2c;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .countdown-card {
        background: linear-gradient(145deg, #1e1e1e, #252525);
        border-radius: 20px;
        border: 1px solid #f1c40f;
    }
    
    .patrocinadores-section {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        padding: 30px;
        margin: 30px 0;
        border: 1px solid #2c2c2c;
    }
    
    .patrocinadores-section h5 {
        color: #f1c40f;
        text-align: center;
        margin-bottom: 20px;
        letter-spacing: 2px;
    }
    
    .row-custom {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }
    
    .col-ingressos {
        flex: 0 0 60%;
        max-width: 60%;
        padding: 0 15px;
    }
    
    .col-noticias {
        flex: 0 0 40%;
        max-width: 40%;
        padding: 0 15px;
    }
    
    .ingressos-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .ingresso-card {
        background: #252525;
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .ingresso-card:hover {
        transform: translateY(-5px);
    }
    
    .btn-comprar {
        background-color: #f1c40f;
        color: #1a1a1a;
        border: none;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-comprar:hover {
        background-color: #ffd700;
        transform: scale(1.02);
    }
    
    .quantidade-input {
        width: 60px;
        padding: 3px 5px;
        border-radius: 8px;
        border: 1px solid #f1c40f;
        background-color: #333;
        color: white;
        text-align: center;
    }
    
    .alert-success-custom {
        background: rgba(40, 167, 69, 0.15);
        border-left: 3px solid #28a745;
        color: #28a745;
        padding: 12px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .alert-error-custom {
        background: rgba(220, 53, 69, 0.15);
        border-left: 3px solid #dc3545;
        color: #dc3545;
        padding: 12px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 992px) {
        .col-ingressos, .col-noticias {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 30px;
        }
        .ingressos-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .ingressos-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .main-container {
            padding: 0 15px;
        }
        .patrocinadores-section {
            padding: 20px;
        }
    }
    </style>
</head>

<body>

    <main>
        <!-- Carrossel -->
        <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"></button>
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
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>

        <div class="main-container">
            
            <?php if(isset($mensagemCompra)): ?>
                <div class="alert-<?php echo $tipoMensagem == 'success' ? 'success-custom' : 'error-custom'; ?>">
                    <?php echo $mensagemCompra; ?>
                </div>
            <?php endif; ?>
            
            <!-- Line-up -->
            <div class="lineup-container">
                <h2 style="color: #f1c40f; margin-bottom: 30px; font-size: 2rem;">LINE-UP 2026</h2>
                <?php if(!empty($artistas)): ?>
                    <?php 
                    $nomes = array_map(function($a) {
                        return strtoupper(htmlspecialchars($a->nome));
                    }, $artistas);
                    
                    $tier1 = array_slice($nomes, 0, 1);
                    $tier2 = array_slice($nomes, 1, 2);
                    $tier3 = array_slice($nomes, 3, 4);
                    $tier4 = array_slice($nomes, 7);
                    ?>
                    
                    <?php if(!empty($tier1)): ?>
                    <div class="tier tier-1">
                        <h1><?php echo implode(' • ', $tier1); ?></h1>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($tier2)): ?>
                    <div class="tier tier-2">
                        <h2><?php echo implode(' • ', $tier2); ?></h2>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($tier3)): ?>
                    <div class="tier tier-3">
                        <h3><?php echo implode(' • ', $tier3); ?></h3>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($tier4)): ?>
                    <div class="tier tier-4">
                        <?php foreach($tier4 as $artista): ?>
                            <span><?php echo $artista; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="tier tier-1">
                        <h1>CHICO BUARQUE</h1>
                    </div>
                    <div class="tier tier-2">
                        <h2>RITA LEE • THE BEATLES</h2>
                    </div>
                    <div class="tier tier-3">
                        <h3>TIM MAIA • ELIS REGINA • BEETHOVEN • AMY WINEHOUSE</h3>
                    </div>
                    <div class="tier tier-4">
                        <span>MARILIA MENDONCA</span>
                        <span>FREDDIE MERCURY</span>
                        <span>TOM JOBIM</span>
                        <span>OZZY OSBOURNE</span>
                        <span>GAL COSTA</span>
                        <span>KURT COBAIN</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Grid de Ingressos e Noticias -->
            <div class="row-custom">
                <div class="col-ingressos">
                    <div class="custom-card p-4">
                        <div class="countdown-card text-center mb-5 p-4">
                            <h4 class="text-uppercase mb-4" style="letter-spacing: 3px; color: #f1c40f; font-weight: bold;">
                                O Portal abre em:
                            </h4>
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <div class="time-unit">
                                    <span id="days" class="d-block h1 fw-bold text-white"><?php echo str_pad($diasRestantes, 2, '0', STR_PAD_LEFT); ?></span>
                                    <small class="text-warning">DIAS</small>
                                </div>
                                <div class="h1 text-secondary">:</div>
                                <div class="time-unit">
                                    <span id="hours" class="d-block h1 fw-bold text-white"><?php echo str_pad($horasRestantes, 2, '0', STR_PAD_LEFT); ?></span>
                                    <small class="text-warning">HORAS</small>
                                </div>
                                <div class="h1 text-secondary">:</div>
                                <div class="time-unit">
                                    <span id="minutes" class="d-block h1 fw-bold text-white"><?php echo str_pad($minutosRestantes, 2, '0', STR_PAD_LEFT); ?></span>
                                    <small class="text-warning">MIN</small>
                                </div>
                            </div>
                        </div>

                        <div class="section-header">
                            <h3>INGRESSOS</h3>
                            <a href="../../ingresso/IngressoList.php" class="btn-ver-todos">VER INGRESSOS</a>
                        </div>

                        <?php if(!empty($ingressos)): ?>
                            <div class="ingressos-grid">
                                <?php foreach(array_slice($ingressos, 0, 4) as $ingresso): ?>
                                <div class="ingresso-card">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-warning fw-bold mb-2"><?php echo strtoupper(htmlspecialchars($ingresso->nome)); ?></h6>
                                        <p class="card-text small text-secondary">
                                            <?php echo htmlspecialchars(substr($ingresso->descricao, 0, 80)); ?>...
                                        </p>
                                        <form method="POST" action="" style="margin-top: 10px;">
                                            <input type="hidden" name="ingresso_id" value="<?php echo $ingresso->id; ?>">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-warning fw-bold">R$ <?php echo number_format($ingresso->valor, 2, ',', '.'); ?></span>
                                                <input type="number" name="quantidade" value="1" min="1" max="<?php echo $ingresso->quantidade; ?>" 
                                                       style="width: 60px; padding: 3px 5px; border-radius: 8px; border: 1px solid #f1c40f; background-color: #333; color: white; text-align: center;">
                                                <button type="submit" name="comprar_ingresso" class="btn-comprar">Comprar</button>
                                            </div>
                                        </form>
                                        <small class="text-muted mt-2 d-block"><?php echo $ingresso->quantidade; ?> disponiveis</small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if(count($ingressos) > 4): ?>
                                <div class="text-center mt-4">
                                    <a href="../../ingresso/IngressoList.php" class="text-warning" style="font-size: 0.8rem;">+ <?php echo (count($ingressos) - 4); ?> outros ingressos disponiveis</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-noticias">
                    <div class="custom-card p-4">
                        <div class="section-header">
                            <h5>NOTICIAS</h5>
                            <a href="../../noticia/NoticiaList.php" class="btn-ver-todos">VER NOTICIAS</a>
                        </div>

                        <?php if(!empty($ultimasNoticias)): ?>
                            <?php foreach($ultimasNoticias as $noticia): ?>
                            <div class="card mb-4 border-0" style="background: #252525; border-radius: 15px;">
                                <div class="card-body">
                                    <h6 class="card-title text-warning fw-bold"><?php echo strtoupper(htmlspecialchars($noticia->titulo)); ?></h6>
                                    <p class="card-text small text-secondary">
                                        <?php echo htmlspecialchars(substr($noticia->resumo, 0, 150)); ?>...
                                    </p>
                                    <a class="btn btn-sm btn-outline-warning rounded-pill px-3" href="noticia_inteira.php?id=<?php echo $noticia->id; ?>">Ler mais</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- PATROCINADORES -->
            <div class="patrocinadores-section">
                <h5>PATROCINADORES OURO</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/funeraria wolf.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/dal bosco.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/funeralagency.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/asw.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/passaro.png" alt="marcas">
                </div>

                <h5>PATROCINADORES PRATA</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/it.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/lucifer.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/logoTGP.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/eljardineiro.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/walkingdeade.png" alt="marcas">
                </div>

                <h5>PATROCINADORES BRONZE</h5>
                <div style="display: flex; justify-content: space-around; align-items: center; width: 100%; padding: 20px 0; flex-wrap: wrap;">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/mc.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/onu.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/dunkindunets.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/pizzahut.png" alt="marcas">
                    <img style="height: 80px; width: auto; margin: 10px;" src="../img/friboi.png" alt="marcas">
                </div>
            </div>
        </div>
    </main>

    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    const dataEvento = new Date("<?php echo $dataEvento; ?>").getTime();
    
    function atualizarContagem() {
        const agora = new Date().getTime();
        const diferenca = dataEvento - agora;
        
        if (diferenca < 0) {
            document.getElementById('days').innerHTML = '00';
            document.getElementById('hours').innerHTML = '00';
            document.getElementById('minutes').innerHTML = '00';
            return;
        }
        
        const dias = Math.floor(diferenca / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diferenca % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((diferenca % (1000 * 60 * 60)) / (1000 * 60));
        
        document.getElementById('days').innerHTML = dias.toString().padStart(2, '0');
        document.getElementById('hours').innerHTML = horas.toString().padStart(2, '0');
        document.getElementById('minutes').innerHTML = minutos.toString().padStart(2, '0');
    }
    
    atualizarContagem();
    setInterval(atualizarContagem, 60000);
    </script>
</body>

</html>
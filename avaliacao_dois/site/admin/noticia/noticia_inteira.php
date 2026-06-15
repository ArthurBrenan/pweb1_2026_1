<?php
// Primeiro, iniciamos a sessão
session_start();

// Incluir arquivos necessários
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

// Instanciar a classe de notícia
$noticiaDB = new db('noticia');

$noticia = null;
$erro = '';

// Verificar se foi passado um ID
if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $noticia = $noticiaDB->find($id);
    
    if (!$noticia) {
        $erro = "Noticia nao encontrada!";
    }
} else {
    $erro = "ID da noticia nao informado!";
}

// Agora sim, depois de todo o processamento PHP, incluímos o header
include '../header2.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* Container principal */
    .noticia-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    
    /* Card da notícia */
    .noticia-card {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        border: 1px solid #2c2c2c;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .noticia-card:hover {
        border-color: #f1c40f;
        box-shadow: 0 10px 30px rgba(241,196,15,0.1);
    }
    
    /* Cabeçalho da notícia */
    .noticia-header {
        padding: 40px 40px 20px 40px;
        border-bottom: 1px solid #2c2c2c;
    }
    
    .noticia-titulo {
        font-size: 2.2rem;
        font-weight: 900;
        color: #f1c40f;
        letter-spacing: 2px;
        margin: 0 0 15px 0;
        line-height: 1.3;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .noticia-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 15px;
    }
    
    .noticia-data {
        color: #888;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Corpo da notícia */
    .noticia-body {
        padding: 30px 40px;
    }
    
    .noticia-resumo {
        background: rgba(241,196,15,0.05);
        border-left: 4px solid #f1c40f;
        padding: 20px 25px;
        margin-bottom: 30px;
        border-radius: 12px;
        font-size: 1.1rem;
        line-height: 1.6;
        color: #c0c0c0;
        font-style: italic;
    }
    
    .noticia-completa {
        color: #b0b0b0;
        line-height: 1.8;
        font-size: 1rem;
        text-align: justify;
    }
    
    /* Botão voltar */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(145deg, #f1c40f, #d4a00a);
        color: #1a1a1a;
        font-weight: bold;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 30px;
        border: none;
        cursor: pointer;
    }
    
    .btn-back:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
    }
    
    /* Alerta de erro */
    .error-card {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        border: 1px solid #dc3545;
        padding: 40px;
        text-align: center;
    }
    
    .error-icon {
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 20px;
    }
    
    .error-title {
        font-size: 1.8rem;
        color: #dc3545;
        margin-bottom: 15px;
    }
    
    .error-message {
        color: #c0c0c0;
        margin-bottom: 30px;
    }
    
    /* Divisória */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #f1c40f, transparent);
        margin: 20px 0;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .noticia-container {
            padding: 20px 15px;
        }
        
        .noticia-header {
            padding: 25px 25px 15px 25px;
        }
        
        .noticia-titulo {
            font-size: 1.5rem;
        }
        
        .noticia-body {
            padding: 20px 25px;
        }
        
        .noticia-resumo {
            font-size: 0.95rem;
            padding: 15px 20px;
        }
        
        .noticia-completa {
            font-size: 0.9rem;
        }
        
        .btn-back {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .noticia-titulo {
            font-size: 1.3rem;
        }
        
        .noticia-meta {
            gap: 15px;
        }
        
        .noticia-data {
            font-size: 0.7rem;
        }
    }
</style>

<div class="noticia-container">
    <?php if ($erro): ?>
        <!-- Página de erro -->
        <div class="error-card">
            <div class="error-icon">
                !
            </div>
            <h1 class="error-title">Ops!</h1>
            <p class="error-message"><?php echo htmlspecialchars($erro); ?></p>
            <a href="../estrutura/paginas/index.php" class="btn-back">
                Voltar para o inicio
            </a>
        </div>
    <?php elseif ($noticia): ?>
        <!-- Notícia encontrada -->
        <div class="noticia-card">
            <div class="noticia-header">
                <h1 class="noticia-titulo"><?php echo htmlspecialchars($noticia->titulo); ?></h1>
                <div class="noticia-meta">
                    <div class="noticia-data">
                        <span>Data: <?php echo date('d/m/Y', strtotime($noticia->data_publicacao)); ?></span>
                    </div>
                    <div class="divider"></div>
                </div>
            </div>
            
            <div class="noticia-body">
                <div class="noticia-resumo">
                    <?php echo nl2br(htmlspecialchars($noticia->resumo)); ?>
                </div>
                
                <div class="noticia-completa">
                    <?php echo nl2br(htmlspecialchars($noticia->noticia_completa)); ?>
                </div>
                
                <div style="text-align: center;">
                    <a href="javascript:history.back()" class="btn-back">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include '../footer.php';
?>
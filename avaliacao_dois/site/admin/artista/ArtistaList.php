<?php
session_start();

// Verificar login
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "../db.class.php";

$db = new db('artista');

// exclusaao logica
if (!empty($_GET['id_deletar'])) {
    try {
        $db->delete($_GET['id_deletar']);
        header('Location: ArtistaList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

$mensagem = '';
if (isset($_GET['deletado'])) {
    $mensagem = "Artista deletado com sucesso!";
}

$busca = '';
$dados = [];

if (!empty($_GET['busca'])) {
    $busca = $_GET['busca'];
    $dados = $db->search($busca);
} else {
    $dados = $db->all();
}

// Ordenar artistas
usort($dados, function($a, $b) {
    return strcmp($a->nome, $b->nome);
});

// dps do processamento, header
include '../header2.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* Título  */
    .page-title {
        font-size: 2rem;
        font-weight: 900;
        color: #f1c40f;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .title-divider {
        width: 60px;
        height: 3px;
        background: #f1c40f;
        margin: 15px auto;
        border-radius: 3px;
    }
    
    /* Botões  */
    .btn-primary-custom {
        background: linear-gradient(145deg, #f1c40f, #d4a00a);
        color: #1a1a1a;
        font-weight: bold;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
        color: #1a1a1a;
    }
    
    .btn-secondary-custom {
        background-color: transparent;
        border: 1px solid #555;
        color: #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .btn-secondary-custom:hover {
        border-color: #f1c40f;
        color: #f1c40f;
    }
    
    .btn-danger-custom {
        background-color: transparent;
        border: 1px solid #dc3545;
        color: #dc3545;
        transition: all 0.3s ease;
    }
    
    .btn-danger-custom:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Campos de formulário */
    .dark-input {
        background-color: #252525 !important;
        border: 1px solid #2c2c2c !important;
        color: #e0e0e0 !important;
        border-radius: 12px;
        padding: 10px 15px;
    }
    
    .dark-input:focus {
        border-color: #f1c40f !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(241,196,15,0.2);
        background-color: #2c2c2c !important;
    }
    
    .dark-input::placeholder {
        color: #666;
    }
    
    /* Tabela */
    .custom-table {
        background: #1a1a1a;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #2c2c2c;
    }
    
    .custom-table thead th {
        background: #1e1e1e;
        color: #f1c40f;
        font-weight: bold;
        letter-spacing: 1px;
        border-bottom: 2px solid #f1c40f;
        padding: 15px;
    }
    
    .custom-table tbody tr {
        border-bottom: 1px solid #2c2c2c;
        transition: all 0.3s ease;
    }
    
    .custom-table tbody tr:hover {
        background-color: rgba(241,196,15,0.08);
    }
    
    .custom-table tbody td {
        padding: 12px 15px;
        color: #c0c0c0;
        background: #1a1a1a;
    }
    
    /* Alertas */
    .alert-custom {
        border-radius: 12px;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: none;
    }
    
    .alert-success-custom {
        background: rgba(40, 167, 69, 0.15);
        border-left: 3px solid #28a745;
        color: #28a745;
    }
    
    .alert-danger-custom {
        background: rgba(220, 53, 69, 0.15);
        border-left: 3px solid #dc3545;
        color: #dc3545;
    }
    
    .alert-info-custom {
        background: rgba(241,196,15,0.1);
        border-left: 3px solid #f1c40f;
        color: #f1c40f;
    }
    
    /* Badge */
    .badge-custom {
        background-color: #1a1a1a;
        color: #f1c40f;
        padding: 5px 10px;
        border-radius: 20px;
        border: 1px solid #f1c40f;
    }
    
    /* ID */
    .artista-id {
        color: #f1c40f;
        font-weight: bold;
    }
    
    /* Nome do artista */
    .artista-name {
        color: #e0e0e0;
        font-weight: 500;
    }
    
    /* Descrição */
    .artista-desc {
        color: #a0a0a0;
        max-width: 400px;
    }
    
    /* Ano da morte */
    .artista-ano {
        color: #f1c40f;
        font-weight: bold;
        text-align: center;
    }
    
    /* Imagem */
    .artista-img-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    /* Responsividade */
    @media (max-width: 992px) {
        .artista-desc {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }
        
        .custom-table thead th {
            font-size: 0.7rem;
            padding: 8px;
        }
        
        .custom-table tbody td {
            font-size: 0.7rem;
            padding: 8px;
        }
        
        .btn-sm {
            font-size: 0.6rem;
            padding: 3px 6px;
        }
        
        .artista-img-preview {
            width: 30px;
            height: 30px;
        }
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        
        <div class="text-center mb-5">
            <h1 class="page-title">LISTA DE ARTISTAS</h1>
            <div class="title-divider"></div>
        </div>
        
        <?php if(!empty($mensagem)): ?>
            <div class="alert-custom alert-success-custom">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($erroDelete)): ?>
            <div class="alert-custom alert-danger-custom">
                <?php echo $erroDelete; ?>
            </div>
        <?php endif; ?>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 w-100 w-md-auto">
                <a href="ArtistaForm.php" class="btn btn-primary-custom fw-bold px-4 py-2" style="border-radius: 50px; letter-spacing: 1px;">
                    + NOVO ARTISTA
                </a>
                <a href="../estrutura/paginas/index.php" class="btn btn-secondary-custom fw-bold px-4 py-2" style="border-radius: 50px;">
                    VOLTAR
                </a>
            </div>
            
            <form method="GET" action="" class="d-flex gap-2 w-100 w-md-auto justify-content-md-end" style="max-width: 380px;">
                <input type="text" name="busca" class="form-control dark-input" style="width: 200px;" 
                       placeholder="Buscar artista..." value="<?php echo htmlspecialchars($busca); ?>">
                <button type="submit" class="btn btn-primary-custom fw-bold px-3" style="border-radius: 50px;">BUSCAR</button>
                <?php if(!empty($busca)): ?>
                    <a href="ArtistaList.php" class="btn btn-secondary-custom" style="border-radius: 50px;">LIMPAR</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if(!empty($busca)): ?>
            <div class="alert-custom alert-info-custom mb-4">
                Resultados para: <strong>"<?php echo htmlspecialchars($busca); ?>"</strong> → <span class="badge-custom"><?php echo count($dados); ?> encontrado(s)</span>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive custom-table">
            <table class="table m-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 70px;">ID</th>
                        <th style="width: 80px;">IMAGEM</th>
                        <th style="width: 200px;">NOME</th>
                        <th>DESCRIÇÃO</th>
                        <th style="width: 120px; text-align: center;">ANO DA MORTE</th>
                        <th class="text-center" style="width: 180px;">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($dados) > 0): ?>
                        <?php foreach($dados as $item): ?>
                        <tr>
                            <td class="ps-3 artista-id"><?php echo $item->id; ?></td>
                            <td>
                                <?php if(!empty($item->imagem)): ?>
                                    <img src="/pweb1_2026_1/avaliacao_dois/site/admin/uploads/<?php echo $item->imagem; ?>" 
                                         class="artista-img-preview" 
                                         alt="<?php echo htmlspecialchars($item->nome); ?>">
                                <?php else: ?>
                                    <span class="text-muted">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td class="artista-name"><?php echo htmlspecialchars($item->nome); ?></td>
                            <td class="artista-desc">
                                <?php echo htmlspecialchars(substr($item->descricao, 0, 80)); ?><?php echo strlen($item->descricao) > 80 ? '...' : ''; ?>
                            </td>
                            <td class="artista-ano text-center"><?php echo !empty($item->ano_morte) ? $item->ano_morte : '---'; ?></td>
                            <td class="text-center">
                                <a href='ArtistaForm.php?id=<?php echo $item->id; ?>' class='btn btn-outline-warning btn-sm' style="border-radius: 8px; margin-right: 5px;">
                                    Editar
                                </a>
                                <a href='ArtistaList.php?id_deletar=<?php echo $item->id; ?>' class='btn btn-danger-custom btn-sm' style="border-radius: 8px;"
                                   onclick='return confirm("Tem certeza que deseja excluir o artista \"<?php echo addslashes($item->nome); ?>\"?")'>
                                    Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <?php if(!empty($busca)): ?>
                                    <em class="text-muted">Nenhum artista encontrado para "<strong><?php echo htmlspecialchars($busca); ?></strong>".</em>
                                <?php else: ?>
                                    <em class="text-muted">Nenhum artista cadastrado no sistema.</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include '../footer.php'; ?>
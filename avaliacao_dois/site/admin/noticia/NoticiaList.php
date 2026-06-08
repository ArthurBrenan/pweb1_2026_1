<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

$db = new db('noticia');

// LÓGICA DE EXCLUSÃO
if (!empty($_GET['id_deletar'])) {
    try {
        $db->delete($_GET['id_deletar']);
        header('Location: NoticiaList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

if (isset($_GET['deletado'])) {
    $mensagem = "Notícia deletada com sucesso!";
}

$busca = '';
$dados = [];

if (!empty($_GET['busca'])) {
    $busca = $_GET['busca'];
    $dados = $db->search($busca); // Certifique-se de que sua db class tenha o método search implementado para notícias
} else {
    $dados = $db->all();
}
?>

<style>
    body {
        background-color: #212529 !important;
    }
    /* Customização para manter os inputs no tema escuro */
    .dark-input {
        background-color: #333 !important;
        border: 1px solid #555 !important;
        color: white !important;
        border-radius: 10px;
    }
    .dark-input::placeholder {
        color: #888;
    }
    /* Ajuste fino na tabela dark */
    .custom-table {
        background-color: #1a1a1a !important;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #333;
    }
    .custom-table th {
        letter-spacing: 1px;
        font-weight: bold;
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        
        <div class="text-center mb-5">
            <h1 style="color: #f1c40f; letter-spacing: 5px; font-size: 2rem; text-transform: uppercase; margin: 0;">
                LISTA DE NOTÍCIAS
            </h1>
            <div style="width: 60px; height: 2px; background: #f1c40f; margin: 15px auto;"></div>
        </div>
        
        <?php if(isset($mensagem)): ?>
            <div class="alert alert-success alert-dismissible fade show" style="background-color: #28a745; border: none; color: white; border-radius: 10px;">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($erroDelete)): ?>
            <div class="alert alert-danger" style="background-color: #dc3545; border: none; color: white; border-radius: 10px;">
                <?php echo $erroDelete; ?>
            </div>
        <?php endif; ?>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 w-100 w-md-auto">
                <a href="noticiaForm.php" class="btn btn-warning fw-bold px-3" style="border-radius: 20px; letter-spacing: 1px; white-space: nowrap;">
                    + NOVA NOTÍCIA
                </a>
                <a href="../../../index.php" class="btn btn-outline-secondary text-light px-3" style="border-radius: 20px; border-color: #555; white-space: nowrap;">
                    VOLTAR
                </a>
            </div>
            
            <form method="GET" action="" class="d-flex gap-2 w-100 w-md-auto justify-content-md-end" style="max-width: 380px;">
                <input type="text" name="busca" class="form-control dark-input" style="width: 180px;" 
                       placeholder="Buscar..." value="<?php echo htmlspecialchars($busca); ?>">
                <button type="submit" class="btn btn-warning fw-bold" style="border-radius: 10px;">BUSCAR</button>
                <?php if(!empty($busca)): ?>
                    <a href="NoticiaList.php" class="btn btn-secondary" style="border-radius: 10px;">LIMPAR</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if(!empty($busca)): ?>
            <div class="alert alert-info" style="background-color: #17a2b8; border: none; color: white; border-radius: 10px; margin-bottom: 20px;">
                Resultados para: <strong>"<?php echo htmlspecialchars($busca); ?>"</strong> &rarr; <span class="badge bg-dark"><?php echo count($dados); ?> encontrado(s)</span>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive custom-table">
            <table class="table table-dark table-hover m-0 align-middle">
                <thead>
                    <tr style="background-color: #f1c40f; color: black;">
                        <th class="py-3 ps-3" style="background-color: #f1c40f; color: black; border: none; width: 70px;">#</th>
                        <th class="py-3" style="background-color: #f1c40f; color: black; border: none; width: 250px;">TÍTULO</th>
                        <th class="py-3" style="background-color: #f1c40f; color: black; border: none;">RESUMO</th>
                        <th class="py-3 text-center" style="background-color: #f1c40f; color: black; border: none; width: 180px;">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dados)): ?>
                        <?php foreach($dados as $item): ?>
                        <tr>
                            <td class="ps-3 fw-bold" style="color: #f1c40f;"><?php echo $item->id; ?></td>
                            <td style="color: white; font-weight: 500;"><?php echo htmlspecialchars($item->titulo); ?></td>
                            <td style="color: #ccc; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($item->resumo); ?>
                            </td>
                            <td class="text-center">
                                <a href='noticiaForm.php?id=<?php echo $item->id; ?>' class='btn btn-sm btn-outline-warning me-1' style="border-radius: 5px;">
                                    Editar
                                </a>
                                <a href='NoticiaList.php?id_deletar=<?php echo $item->id; ?>' class='btn btn-sm btn-danger' style="border-radius: 5px;"
                                   onclick='return confirm("Tem certeza que deseja excluir a notícia \"<?php echo htmlspecialchars($item->titulo); ?>\"?")'>
                                    Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <em>Nenhuma notícia encontrada no sistema.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include '../footer.php'; ?>
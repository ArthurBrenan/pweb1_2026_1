<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

$db = new db('artista');

// LÓGICA DE EXCLUSÃO
if (!empty($_GET['id_deletar'])) {
    try {
        // Buscar artista para deletar a imagem também
        $artista = $db->find($_GET['id_deletar']);
        if($artista && !empty($artista->imagem)) {
            $caminhoImagem = '../uploads/' . $artista->imagem;
            if(file_exists($caminhoImagem)) {
                unlink($caminhoImagem); // Deleta a imagem da pasta
            }
        }
        
        $db->delete($_GET['id_deletar']);
        header('Location: ArtistaList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

// Mensagem de sucesso após deletar
if (isset($_GET['deletado'])) {
    $mensagem = "Artista deletado com sucesso!";
}

// LÓGICA DE BUSCA
$busca = '';
$dados = [];

if (!empty($_GET['busca'])) {
    $busca = $_GET['busca'];
    $dados = $db->search($busca);
} else {
    $dados = $db->all();
}
?>

<div class="row mb-3">
    <div class="col">
        <?php if(isset($mensagem)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($erroDelete)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $erroDelete; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="ArtistaForm.php" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Novo Artista
                </a>
                <a href="../../../index.php" class="btn btn-primary">
                    <i class="fa-solid fa-home"></i> Voltar
                </a>
            </div>
            
            <!-- Campo de Busca -->
            <form method="GET" action="" class="d-flex">
                <input type="text" name="busca" class="form-control me-2" placeholder="Buscar por nome do artista..." value="<?php echo htmlspecialchars($busca); ?>" style="width: 300px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Buscar
                </button>
                <?php if(!empty($busca)): ?>
                    <a href="ArtistaList.php" class="btn btn-secondary ms-2">
                        <i class="fa-solid fa-times"></i> Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if(!empty($busca)): ?>
            <div class="alert alert-info">
                Resultados para: <strong><?php echo htmlspecialchars($busca); ?></strong> 
                (<?php echo count($dados); ?> encontrado(s))
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Foto</th>
                <th scope="col">Nome</th>
                <th scope="col">Descrição</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dados) > 0): ?>
                <?php foreach($dados as $item): ?>
                <tr>
                    <th scope='row'><?php echo $item->id; ?></th>
                    <td class='text-center'>
                        <?php if(!empty($item->imagem)): ?>
                            <img src="../uploads/<?php echo $item->imagem; ?>" alt="<?php echo htmlspecialchars($item->nome); ?>" style="width: 60px; height: 60px; object-fit: cover;" class="img-thumbnail">
                        <?php else: ?>
                            <span class="text-muted">Sem foto</span>
                        <?php endif; ?>
                     </td>
                    <td><?php echo htmlspecialchars($item->nome); ?></td>
                    <td><?php echo htmlspecialchars(substr($item->descricao, 0, 100)); ?><?php echo strlen($item->descricao) > 100 ? '...' : ''; ?></td>
                    <td class='text-center'>
                        <a href='ArtistaForm.php?id=<?php echo $item->id; ?>' class='btn btn-sm btn-primary' title='Editar'>
                            Editar
                        </a>
                        
                        <a href='ArtistaList.php?id_deletar=<?php echo $item->id; ?>' 
                            class='btn btn-sm btn-danger' 
                            onclick='return confirm("Tem certeza que deseja excluir o artista \"<?php echo addslashes($item->nome); ?>\"?")'>
                                Excluir
                        </a>
                     </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">
                        <?php if(!empty($busca)): ?>
                            Nenhum artista encontrado para "<strong><?php echo htmlspecialchars($busca); ?></strong>".
                        <?php else: ?>
                            Nenhum artista cadastrado.
                        <?php endif; ?>
                     </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include '../footer.php';
?>
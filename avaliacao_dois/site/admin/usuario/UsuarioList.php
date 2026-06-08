<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

$db = new db('usuario');

// LÓGICA DE EXCLUSÃO
if (!empty($_GET['id_deletar'])) {
    try {
        $db->delete($_GET['id_deletar']);
        header('Location: UsuarioList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

if (isset($_GET['deletado'])) {
    $mensagem = "Usuário deletado com sucesso!";
}

$busca = '';
$dados = [];

if (!empty($_GET['busca'])) {
    $busca = $_GET['busca'];
    $dados = $db->search($busca);
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
                LISTA DE USUÁRIOS
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
                <a href="usuarioForm.php" class="btn btn-warning fw-bold px-3" style="border-radius: 20px; letter-spacing: 1px; white-space: nowrap;">
                    + NOVO USUÁRIO
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
                    <a href="UsuarioList.php" class="btn btn-secondary" style="border-radius: 10px;">LIMPAR</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if(!empty($busca)): ?>
            <div class="alert alert-info" style="background-color: #90dda5; border: none; color: white; border-radius: 10px; margin-bottom: 20px;">
                Resultados para: <strong>"<?php echo htmlspecialchars($busca); ?>"</strong> &rarr; <span class="badge bg-dark"><?php echo count($dados); ?> encontrado(s)</span>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive custom-table">
            <table class="table table-dark table-hover m-0 align-middle">
                <thead>
                    <tr style="background-color:  #959090; color: black;">
                        <th class="py-3 ps-3" style="background-color:  #959090; color: black; border: none;">#</th>
                        <th class="py-3" style="background-color:  #959090; color: black; border: none;">NOME</th>
                        <th class="py-3" style="background-color:  #959090; color: black; border: none;">IDADE</th>
                        <th class="py-3" style="background-color:  #959090; color: black; border: none;">TELEFONE</th>
                        <th class="py-3" style="background-color:  #959090; color: black; border: none;">EMAIL</th>
                        <th class="py-3 text-center" style="background-color:  #959090; color: black; border: none; width: 180px;">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dados)): ?>
                        <?php foreach($dados as $item): ?>
                        <tr>
                            <td class="ps-3 fw-bold" style="color: #f1c40f;"><?php echo $item->id; ?></td>
                            <td><?php echo htmlspecialchars($item->nome); ?></td>
                            <td><?php echo $item->idade; ?> anos</td>
                            <td><?php echo !empty($item->telefone) ? htmlspecialchars($item->telefone) : '<span class="text-muted small">Não informado</span>'; ?></td>
                            <td style="color: white;"><?php echo htmlspecialchars($item->email); ?></td> <td class="text-center">
                                <a href='usuarioForm.php?id=<?php echo $item->id; ?>' class='btn btn-sm btn-outline-warning me-1' style="border-radius: 5px;">
                                    Editar
                                </a>
                                <a href='UsuarioList.php?id_deletar=<?php echo $item->id; ?>' class='btn btn-sm btn-danger' style="border-radius: 5px;"
                                   onclick='return confirm("Tem certeza que deseja excluir o usuário \"<?php echo htmlspecialchars($item->nome); ?>\"?")'>
                                    Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <em>Nenhum usuário encontrado no sistema.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include '../footer.php'; ?>
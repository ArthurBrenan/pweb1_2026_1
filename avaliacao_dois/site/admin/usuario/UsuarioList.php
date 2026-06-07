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
        // Redireciona para a mesma página para evitar reenvio
        header('Location: UsuarioList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

// Mensagem de sucesso após deletar
if (isset($_GET['deletado'])) {
    $mensagem = "Usuário deletado com sucesso!";
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
            <a href="usuarioForm.php" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Novo Usuário
            </a>
            <a href="../../../index.php" class="btn btn-primary">
                    <i class="fa-solid fa-home"></i> Voltar
            </a>
            
            <!-- Campo de Busca -->
            <form method="GET" action="" class="d-flex">
                <input type="text" name="busca" class="form-control me-2" placeholder="Buscar por nome, email ou telefone..." value="<?php echo htmlspecialchars($busca); ?>" style="width: 300px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Buscar
                </button>
                <?php if(!empty($busca)): ?>
                    <a href="UsuarioList.php" class="btn btn-secondary ms-2">
                        <i class="fa-solid fa-times"></i> Limpar
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Mostrar resultado da busca -->
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
                <th scope="col">Nome</th>
                <th scope="col">Idade</th>
                <th scope="col">Telefone</th>
                <th scope="col">Email</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dados) > 0): ?>
                <?php foreach($dados as $item): ?>
                <tr>
                    <th scope='row'><?php echo $item->id; ?></th>
                    <td><?php echo htmlspecialchars($item->nome); ?></td>
                    <td><?php echo $item->idade; ?> anos</td>
                    <td><?php echo htmlspecialchars($item->telefone); ?></td>
                    <td><?php echo htmlspecialchars($item->email); ?></td>
                    <td class='text-center'>
                        <a href='usuarioForm.php?id=<?php echo $item->id; ?>' class='btn btn-sm btn-primary' title='Editar'>
                            Editar
                        </a>
                        
                        <a href='UsuarioList.php?id_deletar=<?php echo $item->id; ?>' 
                            class='btn btn-sm btn-danger' 
                            onclick='return confirm("Tem certeza que deseja excluir este usuário?")'>
                                Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">
                        <?php if(!empty($busca)): ?>
                            Nenhum usuário encontrado para "<strong><?php echo htmlspecialchars($busca); ?></strong>".
                        <?php else: ?>
                            Nenhum usuário cadastrado.
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
<?php
// Primeiro, iniciamos a sessão
session_start();

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "../db.class.php";

$db = new db('usuario');

// LÓGICA DE EXCLUSÃO - isso precisa vir ANTES de qualquer saída HTML
if (!empty($_GET['id_deletar'])) {
    try {
        $db->delete($_GET['id_deletar']);
        header('Location: UsuarioList.php?deletado=1');
        exit;
    } catch(Exception $e) {
        $erroDelete = "Erro ao deletar: " . $e->getMessage();
    }
}

$mensagem = '';
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

// Agora sim, depois de todo o processamento PHP, incluímos os headers
include '../header2.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* Título principal */
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
    
    /* Botões padrão */
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
    
    /* Tabela - SEM fundo branco */
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
    
    /* ID do usuário */
    .user-id {
        color: #f1c40f;
        font-weight: bold;
    }
    
    /* Responsividade */
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
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        
        <div class="text-center mb-5">
            <h1 class="page-title">LISTA DE USUÁRIOS</h1>
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
                <a href="usuarioForm.php" class="btn btn-primary-custom fw-bold px-4 py-2" style="border-radius: 50px; letter-spacing: 1px;">
                    + NOVO USUÁRIO
                </a>
                <a href="../estrutura/paginas/index.php" class="btn btn-secondary-custom fw-bold px-4 py-2" style="border-radius: 50px;">
                    VOLTAR
                </a>
            </div>
            
            <form method="GET" action="" class="d-flex gap-2 w-100 w-md-auto justify-content-md-end" style="max-width: 380px;">
                <input type="text" name="busca" class="form-control dark-input" style="width: 200px;" 
                       placeholder="Buscar usuário..." value="<?php echo htmlspecialchars($busca); ?>">
                <button type="submit" class="btn btn-primary-custom fw-bold px-3" style="border-radius: 50px;">BUSCAR</button>
                <?php if(!empty($busca)): ?>
                    <a href="UsuarioList.php" class="btn btn-secondary-custom" style="border-radius: 50px;">LIMPAR</a>
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
                        <th class="ps-3">ID</th>
                        <th>NOME</th>
                        <th>IDADE</th>
                        <th>TELEFONE</th>
                        <th>EMAIL</th>
                        <th class="text-center">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dados)): ?>
                        <?php foreach($dados as $item): ?>
                        <tr>
                            <td class="ps-3 user-id"><?php echo $item->id; ?></td>
                            <td><?php echo htmlspecialchars($item->nome); ?></td>
                            <td><?php echo $item->idade; ?> anos</td>
                            <td><?php echo !empty($item->telefone) ? htmlspecialchars($item->telefone) : '<span class="text-muted small">Não informado</span>'; ?></td>
                            <td><?php echo htmlspecialchars($item->email); ?></td>
                            <td class="text-center">
                                <a href='usuarioForm.php?id=<?php echo $item->id; ?>' class='btn btn-outline-warning btn-sm' style="border-radius: 8px; margin-right: 5px;">
                                    Editar
                                </a>
                                <a href='UsuarioList.php?id_deletar=<?php echo $item->id; ?>' class='btn btn-danger-custom btn-sm' style="border-radius: 8px;"
                                   onclick='return confirm("Tem certeza que deseja excluir o usuário \"<?php echo htmlspecialchars($item->nome); ?>\"?")'>
                                    Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <em class="text-muted">Nenhum usuário encontrado no sistema.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include '../footer.php'; ?>
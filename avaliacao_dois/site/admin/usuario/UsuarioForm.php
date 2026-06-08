<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

$db = new db('usuario');

$success = '';
$actionError = '';
$errors = [];
$data = null;

if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

if(!empty($_POST)){
    
    $data = (object) $_POST; 
    
    try {    
        $errors = [];
        
        if(empty($_POST['nome'])){
            $errors[] = "<li>O nome é obrigatório</li>";
        }
        if(empty($_POST['email'])){
            $errors[] = "<li>O email é obrigatório</li>";
        }
        if(empty($_POST['idade'])){
            $errors[] = "<li>A idade é obrigatória</li>";
        }
        
        if(empty($_POST['id']) && empty($_POST['senha'])){
            $errors[] = "<li>A senha é obrigatória para novos cadastros</li>";
        }

        if(empty($_POST['id'])) {
            $usuarioExistente = $db->findBy('email', $_POST['email']);
            if($usuarioExistente) {
                $errors[] = "<li>Este email já está cadastrado!</li>";
            }
        }

        if(empty($errors)){
            
            $dadosParaSalvar = $_POST;
            
            if(!empty($dadosParaSalvar['senha'])) {
                $dadosParaSalvar['senha'] = password_hash($dadosParaSalvar['senha'], PASSWORD_DEFAULT);
            } else {
                unset($dadosParaSalvar['senha']); 
            }

            if (!empty($dadosParaSalvar['id'])) {
                $db->update($dadosParaSalvar);
                $success = "Atualizado com sucesso!";
            } else {
                if(isset($dadosParaSalvar['id'])) {
                    unset($dadosParaSalvar['id']);
                }
                $db->store($dadosParaSalvar);
                $success = "Registrado com sucesso!";
            }
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'UsuarioList.php';
                    }, 2000);
                  </script>";
        }
        
    } catch (Exception $e){
        $actionError = "Erro: " . $e->getMessage();
    }
}
?>

<style>
    body {
        background-color: #212529 !important;
    }
</style>
<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md col-lg-6">
            
            <!-- Card com estilo do festival -->
            <div class="p-4 text-white shadow-lg" style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333;">
                
                <!-- Título estilizado -->
                <div class="text-center mb-4">
                    <h2 class="text-uppercase fw-bold" style="color: #f1c40f; letter-spacing: 3px; font-size: 2rem; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1);">
                        <?php echo !empty($data->id) ? 'EDITAR USUÁRIO' : 'CADASTRO DE USUÁRIO'; ?>
                    </h2>
                    <div style="width: 80px; height: 2px; background: #f1c40f; margin: 10px auto;"></div>
                </div>
                
                <?php actionMessage($success, $actionError); ?>
                <?php showValidationError($errors); ?>
                
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

                    <div class="row"> 
                        <div class="col-12 mb-3">
                            <label for="nome" class="form-label text-warning fw-bold" style="letter-spacing: 1px;">NOME COMPLETO</label>
                            <input type="text" name="nome" class="form-control bg-dark text-white border-secondary" 
                                   style="border-radius: 10px; padding: 12px;"
                                   value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="email" class="form-label text-warning fw-bold" style="letter-spacing: 1px;">E-MAIL</label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" 
                                   style="border-radius: 10px; padding: 12px;"
                                   value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="idade" class="form-label text-warning fw-bold" style="letter-spacing: 1px;">IDADE</label>
                            <input type="number" name="idade" class="form-control bg-dark text-white border-secondary" 
                                   style="border-radius: 10px; padding: 12px;"
                                   value="<?php echo isset($data->idade) ? $data->idade : ''; ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label text-warning fw-bold" style="letter-spacing: 1px;">TELEFONE</label>
                            <input type="text" name="telefone" class="form-control bg-dark text-white border-secondary" 
                                   style="border-radius: 10px; padding: 12px;"
                                   value="<?php echo isset($data->telefone) ? htmlspecialchars($data->telefone) : ''; ?>">
                        </div>

                        <div class="col-12 mb-4">
                            <label for="senha" class="form-label text-warning fw-bold" style="letter-spacing: 1px;">SENHA</label>
                            <input type="password" name="senha" class="form-control bg-dark text-white border-secondary" 
                                   style="border-radius: 10px; padding: 12px;"
                                   placeholder="<?php echo !empty($data->id) ? 'Deixe em branco para manter a atual' : 'Digite sua senha'; ?>">
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 py-2" 
                                style="border-radius: 30px; letter-spacing: 1px;">
                            <i class="fa-solid fa-check"></i> ENVIAR
                        </button>
                        <a href="UsuarioList.php" class="btn btn-outline-secondary px-4 py-2" 
                           style="border-radius: 30px; letter-spacing: 1px; color: #fff; border-color: #555;">
                            <i class="fa-solid fa-arrow-left"></i> VOLTAR
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>
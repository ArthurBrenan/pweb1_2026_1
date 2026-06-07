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
    // Remove o id se existir (está vazio)
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

<div class="col">
    <?php actionMessage($success, $actionError); ?>
    <?php showValidationError($errors); ?>
    
    <form action="" method="post">
        <h3>Cadastro de Usuário</h3>
        
        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

        <div class="row"> 
            <div class="col-6 mb-2">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" class="form-control" value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>">
            </div>
            
            <div class="col-6 mb-2">
                <label for="email">Email: </label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>">
            </div>

            <div class="col-6 mb-2">
                <label for="idade">Idade: </label>
                <input type="number" name="idade" class="form-control" value="<?php echo isset($data->idade) ? $data->idade : ''; ?>">
            </div>
            
            <div class="col-6 mb-2">
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" class="form-control" value="<?php echo isset($data->telefone) ? htmlspecialchars($data->telefone) : ''; ?>">
            </div>

            <div class="col-6 mb-2">
                <label for="senha">Senha: </label>
                <input type="password" name="senha" class="form-control" placeholder="<?php echo !empty($data->id) ? 'Deixe em branco para manter a atual' : ''; ?>">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Enviar</button>
            <a href="UsuarioList.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>

<?php
include '../footer.php';
?>
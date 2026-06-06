<?php
// TOPO DO ARQUIVO
include './site/admin/header.php'; 
include './site/admin/autenticacao.php'; 
include_once "./site/admin/db.class.php"; 

$db = new db('usuario');

$success = '';
$actionError = '';
$errors = [];
$data = null; // Inicializa a variável para evitar avisos no formulário vazio

// Se vier um ID via GET (URL), significa que estamos EDITANDO um usuário existente
if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

if(!empty($_POST)){
    // Guarda o que o usuário digitou para não perder os dados caso dê erro de validação
    $data = (object) $_POST; 
    
    try {    
        // Validações obrigatorias
        if(empty($_POST['nome'])){
            $errors[] = "<li>O nome é obrigatório</li>";
        }
        if(empty($_POST['email'])){
            $errors[] = "<li>O email é obrigatório</li>";
        }
        if(empty($_POST['idade'])){
            $errors[] = "<li>A idade é obrigatória</li>";
        }
        
        // Na edição, a senha pode ser opcional. Mas no cadastro novo ela é obrigatória:
        if(empty($_POST['id']) && empty($_POST['senha'])){
            $errors[] = "<li>A senha é obrigatória para novos cadastros</li>";
        }

        if(empty($errors)){
            
            // Criptografa a senha antes de mandar pro banco
            if(!empty($_POST['senha'])) {
                $_POST['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            } else {
                // Se estiver editando e deixou a senha em branco, mantém a senha antiga
                unset($_POST['senha']); 
            }

            // Se tiver ID preenchido no formulário, atualiza. Se não, cria um novo.
            if (!empty($_POST['id'])) {
                $db->update($_POST);
                $success = "Atualizado com sucesso";
            } else {
                $db->store($_POST);
                $success = "Registrado com sucesso";
            }

            // Redirecionamento desativado por padrão:
            // redirect('./UsuarioList.php');
        }
        
    } catch (PDOException $e){
        $actionError = $e->getMessage();
    } catch (Exception $e){
        $actionError = $e->getMessage();
    }
}
?>

<div class="col">
    <?php if (function_exists('actionMessage')) { actionMessage($success, $actionError); } ?>
    <?php if (function_exists('showValidationError')) { showValidationError($errors); } ?>
    
    <form action="index.php" method="post">
        <h3>Cadastro de Usuário</h3>
        
        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

        <div class="row"> 
            <div class="col-6 mb-2">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" class="form-control" value="<?php echo isset($data->nome) ? $data->nome : ''; ?>">
            </div>
            
            <div class="col-6 mb-2">
                <label for="email">Email: </label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($data->email) ? $data->email : ''; ?>">
            </div>

            <div class="col-6 mb-2">
                <label for="idade">Idade: </label>
                <input type="number" name="idade" class="form-control" value="<?php echo isset($data->idade) ? $data->idade : ''; ?>">
            </div>
            
            <div class="col-6 mb-2">
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" class="form-control" value="<?php echo isset($data->telefone) ? $data->telefone : ''; ?>">
            </div>

            <div class="col-6 mb-2">
                <label for="senha">Senha: </label>
                <input type="password" name="senha" class="form-control" placeholder="<?php echo !empty($data->id) ? 'Deixe em branco para manter a atual' : ''; ?>">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Enviar</button>
            <a href="./UsuarioList.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>

<?php
// FIM DO ARQUIVO
include './site/admin/footer.php';
?>
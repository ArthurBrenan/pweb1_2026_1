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

// Se vier um ID via GET (URL), significa que estamos EDITANDO um usuário existente
if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

// PROCESSAR O FORMULÁRIO QUANDO FOR SUBMETIDO
if(!empty($_POST)){
    
    // Guarda o que o usuário digitou para não perder os dados caso dê erro
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

        // Verificar se email já existe (apenas para novos cadastros)
        if(empty($_POST['id'])) {
            $usuarioExistente = $db->findBy('email', $_POST['email']);
            if($usuarioExistente) {
                $errors[] = "<li>Este email já está cadastrado!</li>";
            }
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = $_POST;
            
            // CRITICAL: Remove o ID se estiver vazio (caso de novo cadastro)
            if(isset($dadosParaSalvar['id']) && empty($dadosParaSalvar['id'])) {
                unset($dadosParaSalvar['id']);
            }
            
            // Criptografa a senha se foi fornecida
            if(!empty($dadosParaSalvar['senha'])) {
                $dadosParaSalvar['senha'] = password_hash($dadosParaSalvar['senha'], PASSWORD_DEFAULT);
            } else {
                // Se estiver editando e deixou a senha em branco, remove do array
                unset($dadosParaSalvar['senha']); 
            }

            // Se tiver ID preenchido no formulário, atualiza. Se não, cria um novo.
            if (!empty($dadosParaSalvar['id'])) {
                $db->update($dadosParaSalvar);
                $success = "Atualizado com sucesso!";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Registrado com sucesso!";
            }
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'UsuarioList.php';
                    }, 2000);
                  </script>";
        }
        
    } catch (PDOException $e){
        $actionError = "Erro no banco de dados: " . $e->getMessage();
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
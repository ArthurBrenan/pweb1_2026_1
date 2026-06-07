<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include './header.php';
include './autenticacao.php';
include_once "./db.class.php";

// Se já estiver logado, redireciona para o index
if(isLoggedIn()) {
    redirect('../../index.php');
}

$db = new db('usuario');

$success = '';
$actionError = '';
$errors = [];
$data = null;

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
        if(empty($_POST['senha'])){
            $errors[] = "<li>A senha é obrigatória</li>";
        } elseif(strlen($_POST['senha']) < 4){
            $errors[] = "<li>A senha deve conter no mínimo 4 caracteres</li>";
        }

        // Verificar se email já existe
        if(empty($errors)) {
            $usuarioExistente = $db->findBy('email', $_POST['email']);
            if($usuarioExistente) {
                $errors[] = "<li>Este email já está cadastrado!</li>";
            }
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'idade' => $_POST['idade'],
                'telefone' => !empty($_POST['telefone']) ? $_POST['telefone'] : '',
                'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
            ];

            $db->store($dadosParaSalvar);
            $success = "Usuário registrado com sucesso! Redirecionando para o login...";
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 0000);
                  </script>";
        }
        
    } catch (PDOException $e){
        if(strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $actionError = "Este email já está cadastrado no sistema!";
        } else {
            $actionError = "Erro no banco de dados: " . $e->getMessage();
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
        <h3>Registrar Usuário</h3>

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
                <input type="password" name="senha" class="form-control" placeholder="Mínimo de 4 caracteres">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="login.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>

<?php
include './footer.php';
?>
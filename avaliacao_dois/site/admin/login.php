<?php
include './header.php';
include './autenticacao.php';
include_once "../admin/db.class.php";

// Se já estiver logado, redireciona para o index da raiz
if(isLoggedIn()) {
    redirect('../../index.php');
}

$db = new db('usuario');
$success = '';
$actionError = '';
$errors = [];
$data = '';

if(!empty($_POST)){
    $data = (object) $_POST;
    
    try{    
        if(empty($_POST['email'])){
            $errors[] = "<li>O email é obrigatório</li>";
        }
        if(empty($_POST['senha'])){
            $errors[] = "<li>A senha é obrigatória</li>";
        } elseif(strlen($_POST['senha']) < 3){
            $errors[] = "<li>A senha deve conter no mínimo 3 caracteres</li>";
        }
        
        if(empty($errors)){
            $usuario = $db->findBy('email', $_POST['email']);  
            
            if($usuario && password_verify($_POST['senha'], $usuario->senha)){
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_nome'] = $usuario->nome;
                $_SESSION['usuario_email'] = $usuario->email;

                $success = "Usuário logado com sucesso!";
                
                // CORRIGIDO: redireciona para o index na RAIZ do avaliacao_dois
                header('Location: ../../index.php');
                exit;
            } else {
                $actionError = "Email ou senha inválidos, por favor tente novamente";
            }
        }
        
    } catch (PDOException $e){
        $actionError = $e->getMessage();
    } catch (Exception $e){
        $actionError = $e->getMessage();
    }
}
?>

<div class="row">
    <?php actionMessage($success, $actionError); ?>
    <?php showValidationError($errors); ?>
    
    <form action="" method="post">
        <h3>Login Usuário</h3>

        <div class="col-6 mb-2">
            <label for="email">Email: </label>
            <input type="email" name="email" class="form-control" value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>">
        </div>
        
        <div class="col-6 mb-2">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" class="form-control">
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Logar</button>
            <a href="registrar.php" class="btn btn-primary">Registre aqui</a>
        </div>
    </form>
</div>

<?php
include './footer.php';
?>
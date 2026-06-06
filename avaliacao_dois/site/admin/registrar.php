<?php
include './header.php';
include_once "./database/db.class.php";

$db = new db('usuario');
$success = '';
$actionError = '';
$errors = [];
$data = '';



if(!empty($_POST)){
    //var_dump($_POST);
    //exit;
    $data = (object) $_POST;
    try{    

        if(empty($_POST['nome'])){
            $errors[] = "<li>O nome é obrigatório</li>";
        }
        if(empty($_POST['email'])){
            $errors[] = "<li>O email é obrigatório</li>";
        }
        if(empty($_POST['senha'])){
            $errors[] = "<li>A senha é obrigatória</li>";

            if(strlen($_POST['senha']) < 3){
                $errors[] = "<li>A senha deve conter no mínimo 3 caracteres</li>";
            }
        }
        
        if(empty($errors)){

            $dado = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'telefone' => $_POST['telefone'] ? $_POST['telefone'] : "",
                'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
            ];

            $db->store($dado);
            $success = "Usuario cadastrado com sucesso! Redirecionando para o login...";

            redirect('./login.php');
        }

        
    } catch (PDOException $e){
        $error = $e->getMessage();
    } catch (Exception $e){
        $error = $e->getMessage();
    }
}

?>


<div class="row">
    <?php actionMessage($success, $actionError) ?>
    <?php showValidationError($errors) ?>
    <form action="registrar.php" method="post">
        h3>Registrar Usuário</h3>
        <div class="col-6">
            <label for="nome">Nome: </label>
            <input type="text" name="nome" class="form-control" value="<?php echo getFormValue($data,'nome'); ?>">
        </div>
        <div class="col-6">
            <label for="email">Email: </label>
            <input type="email" name="email" class="form-control" value="<?php echo getFormValue($data,'email'); ?>">
        </div>
        <div class="col-6">
            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" class="form-control" value="<?php echo getFormValue($data,'telefone'); ?>">
        </div>
        <div class="col-6">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" class="form-control" value="<?php echo getFormValue($data,'senha'); ?>">
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-success">Enviar</button>
            <a href="./login.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>


<?php
include './footer.php';
?>
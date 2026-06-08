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

<style>
    body {
        background-color: #212529 !important;
    }
</style>
<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-6 col-lg-5">
                
                <!-- Card de login -->
                <div style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333; padding: 40px;">
                    
                    <!-- Título -->
                    <div class="text-center mb-4">
                        <h1 style="color: #f1c40f; letter-spacing: 5px; font-size: 2rem; text-transform: uppercase; margin: 0;">
                            LOGIN
                        </h1>
                        <div style="width: 60px; height: 2px; background: #f1c40f; margin: 15px auto;"></div>
                        <p style="color: #888; font-size: 0.8rem; margin: 0;">Acesso ao painel administrativo</p>
                    </div>
                    
                    <!-- Mensagens -->
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success" style="background-color: #28a745; border: none; color: white; border-radius: 10px;">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($actionError)): ?>
                        <div class="alert alert-danger" style="background-color: #dc3545; border: none; color: white; border-radius: 10px;">
                            <?php echo $actionError; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger" style="background-color: #dc3545; border: none; color: white; border-radius: 10px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach($errors as $error): ?>
                                    <?php echo $error; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulário -->
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">E-MAIL</label>
                            <input type="email" name="email" class="form-control" 
                                   style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                   value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>"
                                   placeholder="seu@email.com">
                        </div>
                        
                        <div class="mb-4">
                            <label for="senha" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">SENHA</label>
                            <input type="password" name="senha" class="form-control" 
                                   style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                   placeholder="Digite sua senha">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2" style="border-radius: 30px; letter-spacing: 2px;">
                                ENTRAR
                            </button>
                            <div class="text-center mt-3">
                                <a href="registrar.php" style="color: #888; text-decoration: none; font-size: 0.8rem;">
                                    Não tem uma conta? <span style="color: #f1c40f;">Registre-se aqui</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include './footer.php';
?>
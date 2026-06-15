<?php
session_start();

// verificar log
if(isset($_SESSION['usuario_id'])) {
    header('Location: /pweb1_2026_1/avaliacao_dois/site/admin/estrutura/paginas/index.php');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

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
                
                // Redireciona
                header('Location: /pweb1_2026_1/avaliacao_dois/site/admin/estrutura/paginas/index.php');
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


require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    .login-card {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        border: 1px solid #2c2c2c;
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        border-color: #f1c40f;
        box-shadow: 0 10px 30px rgba(241,196,15,0.1);
    }
    
    .login-title {
        font-size: 2rem;
        font-weight: 900;
        color: #f1c40f;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .login-divider {
        width: 60px;
        height: 3px;
        background: #f1c40f;
        margin: 15px auto;
        border-radius: 3px;
    }
    
    .login-subtitle {
        color: #888;
        font-size: 0.8rem;
        margin: 0;
        letter-spacing: 1px;
    }
    
    .form-label-custom {
        color: #f1c40f;
        letter-spacing: 1px;
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control-custom {
        background-color: #252525;
        border: 1px solid #2c2c2c;
        color: #e0e0e0;
        border-radius: 12px;
        padding: 12px 15px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .form-control-custom:focus {
        background-color: #2c2c2c;
        border-color: #f1c40f;
        outline: none;
        box-shadow: 0 0 0 2px rgba(241,196,15,0.2);
        color: #fff;
    }
    
    .form-control-custom::placeholder {
        color: #666;
    }
    
    .btn-login {
        background: linear-gradient(145deg, #f1c40f, #d4a00a);
        color: #1a1a1a;
        font-weight: bold;
        padding: 12px;
        border-radius: 50px;
        letter-spacing: 2px;
        border: none;
        width: 100%;
        transition: all 0.3s ease;
        font-size: 1rem;
        cursor: pointer;
    }
    
    .btn-login:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
    }
    
    .register-link {
        color: #888;
        text-decoration: none;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    
    .register-link:hover {
        color: #f1c40f;
    }
    
    .register-link span {
        color: #f1c40f;
    }
    
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
    
    .alert-danger-custom ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-danger-custom li {
        color: #dc3545;
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px; display: flex; align-items: center;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-6 col-lg-5">
                
                <!-- Card login -->
                <div class="login-card" style="padding: 40px;">
                    
                    <!-- Título -->
                    <div class="text-center mb-4">
                        <h1 class="login-title">LOGIN</h1>
                        <div class="login-divider"></div>
                        <p class="login-subtitle">Acesso ao sistema</p>
                    </div>
                    
                    <!-- Mensagens -->
                    <?php if(!empty($success)): ?>
                        <div class="alert-custom alert-success-custom">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($actionError)): ?>
                        <div class="alert-custom alert-danger-custom">
                            <?php echo $actionError; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert-custom alert-danger-custom">
                            <ul>
                                <?php foreach($errors as $error): ?>
                                    <?php echo $error; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulário -->
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label-custom">E-MAIL</label>
                            <input type="email" name="email" class="form-control-custom" 
                                   value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>"
                                   placeholder="seu@email.com">
                        </div>
                        
                        <div class="mb-4">
                            <label for="senha" class="form-label-custom">SENHA</label>
                            <input type="password" name="senha" class="form-control-custom" 
                                   placeholder="Digite sua senha">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-login">
                                ENTRAR
                            </button>
                            <div class="text-center mt-3">
                                <a href="../../registrar.php" class="register-link">
                                    Não tem uma conta? <span>Registre-se aqui</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
// Primeiro, iniciamos a sessão
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include './autenticacao.php';
include_once "./db.class.php";

// Se já estiver logado, redireciona para o index
if(isLoggedIn()) {
    header('Location: ../../index.php');
    exit;
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
            $success = "Usuario registrado com sucesso! Redirecionando para o login...";
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'estrutura/paginas/login.php';
                    }, 2000);
                  </script>";
        }
        
    } catch (PDOException $e){
        if(strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $actionError = "Este email ja esta cadastrado no sistema!";
        } else {
            $actionError = "Erro no banco de dados: " . $e->getMessage();
        }
    } catch (Exception $e){
        $actionError = "Erro: " . $e->getMessage();
    }
}

// Agora sim, depois de todo o processamento PHP, incluímos o header
include './header.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* Card estilizado */
    .form-card {
        background: linear-gradient(145deg, #1e1e1e, #161616);
        border-radius: 30px;
        border: 1px solid #2c2c2c;
        transition: all 0.3s ease;
        padding: 40px;
    }
    
    .form-card:hover {
        border-color: #f1c40f;
        box-shadow: 0 10px 30px rgba(241,196,15,0.1);
    }
    
    /* Título */
    .form-title {
        font-size: 2rem;
        font-weight: 900;
        color: #f1c40f;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .title-divider {
        width: 80px;
        height: 3px;
        background: #f1c40f;
        margin: 15px auto;
        border-radius: 3px;
    }
    
    .subtitle {
        color: #888;
        font-size: 0.85rem;
        text-align: center;
    }
    
    /* Labels */
    .form-label-custom {
        color: #f1c40f;
        letter-spacing: 1px;
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
    }
    
    /* Campos de input */
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
    
    /* Botão */
    .btn-submit {
        background: linear-gradient(145deg, #f1c40f, #d4a00a);
        color: #1a1a1a;
        font-weight: bold;
        padding: 12px 30px;
        border-radius: 50px;
        letter-spacing: 2px;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
    }
    
    .login-link {
        color: #888;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 15px;
    }
    
    .login-link:hover {
        color: #f1c40f;
    }
    
    .login-link span {
        color: #f1c40f;
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
    
    .alert-danger-custom ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-danger-custom li {
        color: #dc3545;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .form-card {
            padding: 25px;
        }
        
        .form-title {
            font-size: 1.5rem;
            letter-spacing: 3px;
        }
        
        .btn-submit {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px; display: flex; align-items: center;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6">
                
                <!-- Card com estilo padronizado -->
                <div class="form-card">
                    
                    <!-- Título estilizado -->
                    <div class="text-center mb-4">
                        <h1 class="form-title">REGISTRAR</h1>
                        <div class="title-divider"></div>
                        <p class="subtitle">Crie sua conta no sistema</p>
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
                            <label for="nome" class="form-label-custom">NOME COMPLETO</label>
                            <input type="text" name="nome" class="form-control-custom" 
                                   value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                   placeholder="Digite seu nome completo">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label-custom">E-MAIL</label>
                            <input type="email" name="email" class="form-control-custom" 
                                   value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>"
                                   placeholder="seu@email.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="idade" class="form-label-custom">IDADE</label>
                                <input type="number" name="idade" class="form-control-custom" 
                                       value="<?php echo isset($data->idade) ? $data->idade : ''; ?>"
                                       placeholder="Sua idade">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label-custom">TELEFONE</label>
                                <input type="text" name="telefone" class="form-control-custom" 
                                       value="<?php echo isset($data->telefone) ? htmlspecialchars($data->telefone) : ''; ?>"
                                       placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="senha" class="form-label-custom">SENHA</label>
                            <input type="password" name="senha" class="form-control-custom" 
                                   placeholder="Minimo de 4 caracteres">
                        </div>

                        <button type="submit" class="btn-submit">
                            REGISTRAR
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="estrutura/paginas/login.php" class="login-link">
                                Ja possui uma conta? <span>Faça login aqui</span>
                            </a>
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
<?php
session_start();

// Verificar log
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

//header
include '../header2.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* Card */
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
        font-size: 1.8rem;
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
    
    /* Botões */
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
    }
    
    .btn-submit:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
    }
    
    .btn-back {
        background-color: transparent;
        border: 1px solid #555;
        color: #e0e0e0;
        padding: 12px 30px;
        border-radius: 50px;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-back:hover {
        border-color: #f1c40f;
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
            font-size: 1.3rem;
            letter-spacing: 3px;
        }
        
        .btn-submit, .btn-back {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px; display: flex; align-items: center;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6">
                
                <!-- Card -->
                <div class="form-card">
                    
                    <!-- Título -->
                    <div class="text-center mb-4">
                        <h1 class="form-title">
                            <?php echo !empty($data->id) ? 'EDITAR USUÁRIO' : 'CADASTRO DE USUÁRIO'; ?>
                        </h1>
                        <div class="title-divider"></div>
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
                        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label-custom">NOME COMPLETO</label>
                            <input type="text" name="nome" class="form-control-custom" 
                                   value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                   placeholder="Digite o nome completo">
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
                                   placeholder="<?php echo !empty($data->id) ? 'Deixe em branco para manter a atual' : 'Digite sua senha'; ?>">
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn-submit">
                                ENVIAR
                            </button>
                            <a href="UsuarioList.php" class="btn-back">
                                VOLTAR
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>
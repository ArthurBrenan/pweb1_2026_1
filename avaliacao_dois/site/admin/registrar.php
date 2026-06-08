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
            
            // Redireciona após 2 segundos (2000ms)
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
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

<style>
    body {
        background-color: #212529 !important;
    }
</style>
<div style="min-height: 80vh; padding: 40px 20px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6">
                
                <div style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333; padding: 40px;">
                    
                    <div class="text-center mb-4">
                        <h1 style="color: #f1c40f; letter-spacing: 5px; font-size: 2rem; text-transform: uppercase; margin: 0;">
                            REGISTRAR
                        </h1>
                        <div style="width: 60px; height: 2px; background: #f1c40f; margin: 15px auto;"></div>
                        <p style="color: #888; font-size: 0.8rem; margin: 0;">Crie sua conta no sistema</p>
                    </div>
                    
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
                    
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nome" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">NOME</label>
                                <input type="text" name="nome" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                       placeholder="Seu nome completo">
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">E-MAIL</label>
                                <input type="email" name="email" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       value="<?php echo isset($data->email) ? htmlspecialchars($data->email) : ''; ?>"
                                       placeholder="seu@email.com">
                            </div>

                            <div class="col-6 mb-3">
                                <label for="idade" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">IDADE</label>
                                <input type="number" name="idade" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       value="<?php echo isset($data->idade) ? $data->idade : ''; ?>"
                                       placeholder="Ex: 25">
                            </div>
                            
                            <div class="col-6 mb-3">
                                <label for="telefone" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">TELEFONE</label>
                                <input type="text" name="telefone" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       value="<?php echo isset($data->telefone) ? htmlspecialchars($data->telefone) : ''; ?>"
                                       placeholder="(00) 00000-0000">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="senha" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">SENHA</label>
                                <input type="password" name="senha" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       placeholder="Mínimo de 4 caracteres">
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2" style="border-radius: 30px; letter-spacing: 2px;">
                                REGISTRAR
                            </button>
                            <div class="text-center mt-3">
                                <a href="login.php" style="color: #888; text-decoration: none; font-size: 0.8rem;">
                                    Já possui uma conta? <span style="color: #f1c40f;">Faça login aqui</span>
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
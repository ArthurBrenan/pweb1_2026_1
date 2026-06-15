<?php
// Primeiro, iniciamos a sessão
session_start();

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "../db.class.php";

$db = new db('ingresso');

$success = '';
$actionError = '';
$errors = [];
$data = null;

// Se vier um ID via GET (URL), significa que estamos EDITANDO um ingresso existente
if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

// PROCESSAR O FORMULÁRIO QUANDO FOR SUBMETIDO
if(!empty($_POST)){
    
    // Guarda o que o usuário digitou para não perder os dados caso dê erro
    $data = (object) $_POST; 
    
    try {    
        // Validações obrigatórias
        if(empty($_POST['nome'])){
            $errors[] = "<li>O nome do ingresso é obrigatório</li>";
        }
        if(empty($_POST['descricao'])){
            $errors[] = "<li>A descrição é obrigatória</li>";
        }
        if(empty($_POST['quantidade']) && $_POST['quantidade'] !== '0'){
            $errors[] = "<li>A quantidade é obrigatória</li>";
        } elseif(!is_numeric($_POST['quantidade']) || $_POST['quantidade'] < 0){
            $errors[] = "<li>A quantidade deve ser um número válido maior ou igual a 0</li>";
        }
        if(empty($_POST['valor']) && $_POST['valor'] !== '0'){
            $errors[] = "<li>O valor é obrigatório</li>";
        } elseif(!is_numeric(str_replace(',', '.', $_POST['valor'])) || floatval(str_replace(',', '.', $_POST['valor'])) < 0){
            $errors[] = "<li>O valor deve ser um número válido maior ou igual a 0</li>";
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'],
                'quantidade' => $_POST['quantidade'],
                'valor' => str_replace(',', '.', $_POST['valor'])
            ];

            // Se tiver ID preenchido no formulário, atualiza. Se não, cria um novo.
            if (!empty($_POST['id'])) {
                $dadosParaSalvar['id'] = $_POST['id'];
                $db->update($dadosParaSalvar);
                $success = "Ingresso atualizado com sucesso! Redirecionando...";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Ingresso cadastrado com sucesso! Redirecionando...";
            }
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'IngressoList.php';
                    }, 2000);
                  </script>";
        }
        
    } catch (PDOException $e){
        $actionError = "Erro no banco de dados: " . $e->getMessage();
    } catch (Exception $e){
        $actionError = "Erro: " . $e->getMessage();
    }
}

// Agora sim, depois de todo o processamento PHP, incluímos o header
include '../header2.php';
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
    
    /* Textarea */
    textarea.form-control-custom {
        resize: vertical;
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
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(241,196,15,0.3);
        background: linear-gradient(145deg, #ffd700, #e6b800);
    }
    
    .back-link {
        color: #888;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 15px;
    }
    
    .back-link:hover {
        color: #f1c40f;
    }
    
    .back-link span {
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
    
    /* Helper text */
    .helper-text {
        color: #666;
        font-size: 0.7rem;
        margin-top: 5px;
        display: block;
    }
    
    /* Row de campos lado a lado */
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .form-row .form-group {
        flex: 1;
        min-width: 150px;
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
        
        .btn-submit {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<div style="min-height: 80vh; padding: 40px 20px; display: flex; align-items: center;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10 col-lg-8">
                
                <!-- Card com estilo padronizado -->
                <div class="form-card">
                    
                    <!-- Título estilizado -->
                    <div class="text-center mb-4">
                        <h1 class="form-title">
                            <?php echo !empty($data->id) ? 'EDITAR INGRESSO' : 'CADASTRO DE INGRESSO'; ?>
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
                            <label for="nome" class="form-label-custom">NOME DO INGRESSO</label>
                            <input type="text" name="nome" class="form-control-custom" 
                                   value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                   placeholder="Ex: Pista VIP, Camarote, Premium...">
                            <small class="helper-text">Identificacao clara do tipo de ingresso.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label-custom">DESCRICAO</label>
                            <textarea name="descricao" class="form-control-custom" rows="3" 
                                      placeholder="O que este ingresso inclui? Vantagens, acessos..."><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                            <small class="helper-text">Maximo de 200 caracteres</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="quantidade" class="form-label-custom">QUANTIDADE DISPONIVEL</label>
                                <input type="number" name="quantidade" class="form-control-custom" 
                                       value="<?php echo isset($data->quantidade) ? $data->quantidade : ''; ?>" 
                                       min="0" step="1"
                                       placeholder="0">
                                <small class="helper-text">Lote total de vendas.</small>
                            </div>

                            <div class="form-group">
                                <label for="valor" class="form-label-custom">VALOR (R$)</label>
                                <input type="text" name="valor" class="form-control-custom" 
                                       value="<?php echo isset($data->valor) ? number_format($data->valor, 2, ',', '.') : ''; ?>" 
                                       placeholder="0,00">
                                <small class="helper-text">Preco unitario em Reais.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <?php echo !empty($data->id) ? 'ATUALIZAR' : 'CADASTRAR'; ?>
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="IngressoList.php" class="back-link">
                                ← Voltar para a <span>Lista de Ingressos</span>
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
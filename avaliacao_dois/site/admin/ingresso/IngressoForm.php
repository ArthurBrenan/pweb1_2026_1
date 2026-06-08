<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
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
        } elseif(!is_numeric($_POST['valor']) || $_POST['valor'] < 0){
            $errors[] = "<li>O valor deve ser um número válido maior ou igual a 0</li>";
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'],
                'quantidade' => $_POST['quantidade'],
                'valor' => str_replace(',', '.', $_POST['valor']) // Converte vírgula para ponto
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
?>

<style>
    body {
        background-color: #212529 !important;
    }
    /* Classe utilitária para manter inputs escuros padronizados */
    .form-dark {
        background-color: #333 !important;
        border: 1px solid #555 !important;
        color: white !important;
        border-radius: 10px;
        padding: 12px;
    }
    .form-dark:focus {
        border-color: #f1c40f !important;
        box-shadow: 0 0 0 0.25rem rgba(241, 196, 15, 0.25) !important;
    }
    .form-label-gold {
        color: #f1c40f;
        letter-spacing: 1px;
        font-weight: bold;
    }
</style>

<div style="background-color: #212529; min-height: 100vh; padding: 40px 20px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10 col-lg-8">
                
                <div style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333; padding: 40px;">
                    
                    <div class="text-center mb-4">
                        <h1 style="color: #f1c40f; letter-spacing: 3px; font-size: 1.8rem; text-transform: uppercase; margin: 0;">
                            <?php echo !empty($data->id) ? 'EDITAR INGRESSO' : 'CADASTRO DE INGRESSO'; ?>
                        </h1>
                        <div style="width: 60px; height: 2px; background: #f1c40f; margin: 15px auto;"></div>
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
                        
                        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

                        <div class="row"> 
                            <div class="col-12 mb-3">
                                <label for="nome" class="form-label form-label-gold">NOME DO INGRESSO</label>
                                <input type="text" name="nome" class="form-control form-dark" 
                                       value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                       placeholder="Ex: Pista VIP, Camarote, Premium...">
                                <small style="color: #888; display: block; margin-top: 4px;">Identificação clara do tipo de ingresso.</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descricao" class="form-label form-label-gold">DESCRIÇÃO</label>
                                <textarea name="descricao" class="form-control form-dark" rows="3" 
                                          placeholder="O que este ingresso inclui? Vantagens, acessos..."><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                                <small style="color: #888; display: block; margin-top: 4px;">Máximo de 200 caracteres</small>
                            </div>

                            <div class="col-6 mb-4">
                                <label for="quantidade" class="form-label form-label-gold">QUANTIDADE DISPONÍVEL</label>
                                <input type="number" name="quantidade" class="form-control form-dark" 
                                       value="<?php echo isset($data->quantidade) ? $data->quantidade : ''; ?>" min="0"
                                       placeholder="0">
                                <small style="color: #888; display: block; margin-top: 4px;">Lote total de vendas.</small>
                            </div>

                            <div class="col-6 mb-4">
                                <label for="valor" class="form-label form-label-gold">VALOR (R$)</label>
                                <input type="text" name="valor" class="form-control form-dark" 
                                       value="<?php echo isset($data->valor) ? number_format($data->valor, 2, ',', '.') : ''; ?>" 
                                       placeholder="0,00">
                                <small style="color: #888; display: block; margin-top: 4px;">Preço unitário em Reais.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2" style="border-radius: 30px; letter-spacing: 2px;">
                                <?php echo !empty($data->id) ? 'ATUALIZAR' : 'CADASTRAR'; ?>
                            </button>
                            <div class="text-center mt-3">
                                <a href="IngressoList.php" style="color: #888; text-decoration: none; font-size: 0.9rem;">
                                    &larr; Voltar para a <span style="color: #f1c40f;">Lista de Ingressos</span>
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
include '../footer.php';
?>
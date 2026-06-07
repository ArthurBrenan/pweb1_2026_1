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
                $success = "Ingresso atualizado com sucesso!";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Ingresso cadastrado com sucesso!";
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

<div class="col">
    <?php actionMessage($success, $actionError); ?>
    <?php showValidationError($errors); ?>
    
    <form action="" method="post">
        <h3><?php echo !empty($data->id) ? 'Editar Ingresso' : 'Cadastro de Ingresso'; ?></h3>
        
        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

        <div class="row"> 
            <div class="col-12 mb-2">
                <label for="nome">Nome do Ingresso: </label>
                <input type="text" name="nome" class="form-control" value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>">
                <small class="text-muted">Ex: Pista VIP, Camarote, Arquibancada, etc.</small>
            </div>
            
            <div class="col-12 mb-2">
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" class="form-control" rows="3"><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                <small class="text-muted">Máximo de 200 caracteres</small>
            </div>

            <div class="col-6 mb-2">
                <label for="quantidade">Quantidade Disponível: </label>
                <input type="number" name="quantidade" class="form-control" value="<?php echo isset($data->quantidade) ? $data->quantidade : ''; ?>" min="0">
                <small class="text-muted">Número de ingressos disponíveis para venda</small>
            </div>

            <div class="col-6 mb-2">
                <label for="valor">Valor (R$): </label>
                <input type="text" name="valor" class="form-control" value="<?php echo isset($data->valor) ? number_format($data->valor, 2, ',', '.') : ''; ?>" placeholder="0,00">
                <small class="text-muted">Preço do ingresso em reais (Ex: 49,90)</small>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success"><?php echo !empty($data->id) ? 'Atualizar' : 'Cadastrar'; ?></button>
            <a href="../../../index.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>

<?php
include '../footer.php';
?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

// Usando a tabela 'noticia' (como mostra a imagem: id, título, resumo, noticia_completa)
$db = new db('noticia');

$success = '';
$actionError = '';
$errors = [];
$data = null;

// Se vier um ID via GET (URL), significa que estamos EDITANDO uma notícia existente
if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

// PROCESSAR O FORMULÁRIO QUANDO FOR SUBMETIDO
if(!empty($_POST)){
    
    // Guarda o que o usuário digitou para não perder os dados caso dê erro
    $data = (object) $_POST; 
    
    try {    
        // Validações obrigatórias
        if(empty($_POST['titulo'])){
            $errors[] = "<li>O título é obrigatório</li>";
        }
        if(empty($_POST['resumo'])){
            $errors[] = "<li>O resumo é obrigatório</li>";
        }
        if(empty($_POST['noticia_completa'])){
            $errors[] = "<li>A notícia completa é obrigatória</li>";
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = [
                'titulo' => $_POST['titulo'],
                'resumo' => $_POST['resumo'],
                'noticia_completa' => $_POST['noticia_completa']
            ];

            // Se tiver ID preenchido no formulário, atualiza. Se não, cria um novo.
            if (!empty($_POST['id'])) {
                $dadosParaSalvar['id'] = $_POST['id'];
                $db->update($dadosParaSalvar);
                $success = "Notícia atualizada com sucesso!";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Notícia cadastrada com sucesso!";
            }
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '../noticia/NoticiaList.php';
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
        <h3><?php echo !empty($data->id) ? 'Editar Notícia' : 'Cadastro de Notícia'; ?></h3>
        
        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

        <div class="row"> 
            <div class="col-12 mb-2">
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" class="form-control" value="<?php echo isset($data->titulo) ? htmlspecialchars($data->titulo) : ''; ?>">
            </div>
            
            <div class="col-12 mb-2">
                <label for="resumo">Resumo: </label>
                <textarea name="resumo" class="form-control" rows="3"><?php echo isset($data->resumo) ? htmlspecialchars($data->resumo) : ''; ?></textarea>
                <small class="text-muted">Máximo de 100 caracteres</small>
            </div>

            <div class="col-12 mb-2">
                <label for="noticia_completa">Notícia Completa: </label>
                <textarea name="noticia_completa" class="form-control" rows="8"><?php echo isset($data->noticia_completa) ? htmlspecialchars($data->noticia_completa) : ''; ?></textarea>
                <small class="text-muted">Máximo de 1000 caracteres</small>
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
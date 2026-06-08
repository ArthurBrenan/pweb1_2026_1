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
                $success = "Notícia atualizada com sucesso! Redirecionando...";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Notícia cadastrada com sucesso! Redirecionando...";
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

<style>
    body {
        background-color: #212529 !important;
    }
</style>

<div style="background-color: #212529; min-height: 100vh; padding: 40px 20px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10 col-lg-8">
                
                <div style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333; padding: 40px;">
                    
                    <div class="text-center mb-4">
                        <h1 style="color: #f1c40f; letter-spacing: 3px; font-size: 1.8rem; text-transform: uppercase; margin: 0;">
                            <?php echo !empty($data->id) ? 'EDITAR NOTÍCIA' : 'CADASTRO DE NOTÍCIA'; ?>
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
                                <label for="titulo" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">TÍTULO</label>
                                <input type="text" name="titulo" class="form-control" 
                                       style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                       value="<?php echo isset($data->titulo) ? htmlspecialchars($data->titulo) : ''; ?>"
                                       placeholder="Digite o título da notícia">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="resumo" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">RESUMO</label>
                                <textarea name="resumo" class="form-control" rows="3" 
                                          style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                          placeholder="Breve resumo da notícia..."><?php echo isset($data->resumo) ? htmlspecialchars($data->resumo) : ''; ?></textarea>
                                <small style="color: #888; display: block; mt-1;">Máximo de 100 caracteres</small>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="noticia_completa" class="form-label" style="color: #f1c40f; letter-spacing: 1px; font-weight: bold;">NOTÍCIA COMPLETA</label>
                                <textarea name="noticia_completa" class="form-control" rows="8" 
                                          style="background-color: #333; border: 1px solid #555; color: white; border-radius: 10px; padding: 12px;"
                                          placeholder="Digite o conteúdo completo da matéria aqui..."><?php echo isset($data->noticia_completa) ? htmlspecialchars($data->noticia_completa) : ''; ?></textarea>
                                <small style="color: #888; display: block; mt-1;">Máximo de 1000 caracteres</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2" style="border-radius: 30px; letter-spacing: 2px;">
                                <?php echo !empty($data->id) ? 'ATUALIZAR' : 'CADASTRAR'; ?>
                            </button>
                            <div class="text-center mt-3">
                                <a href="../noticia/NoticiaList.php" style="color: #888; text-decoration: none; font-size: 0.9rem;">
                                    &larr; Voltar para a <span style="color: #f1c40f;">Lista de Notícias</span>
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
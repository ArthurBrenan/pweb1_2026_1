<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../autenticacao.php';
include_once "../db.class.php";

$db = new db('artista');

$success = '';
$actionError = '';
$errors = [];
$data = null;

// Se vier um ID via GET (URL), significa que estamos EDITANDO um artista existente
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
            $errors[] = "<li>O nome do artista é obrigatório</li>";
        }
        if(empty($_POST['descricao'])){
            $errors[] = "<li>A descrição é obrigatória</li>";
        }

        // Processar upload da imagem
        $nomeImagem = '';
        if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $arquivoTmp = $_FILES['imagem']['tmp_name'];
            $nomeOriginal = $_FILES['imagem']['name'];
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            $tamanho = $_FILES['imagem']['size'];
            
            // Validar extensão
            if(!in_array($extensao, $extensoesPermitidas)) {
                $errors[] = "<li>Formato de imagem não permitido. Use: JPG, PNG, GIF ou WEBP</li>";
            }
            
            // Validar tamanho (máximo 5MB)
            if($tamanho > 5 * 1024 * 1024) {
                $errors[] = "<li>A imagem deve ter no máximo 5MB</li>";
            }
            
            if(empty($errors)) {
                // Criar nome único para a imagem
                $nomeImagem = uniqid() . '.' . $extensao;
                
                // Caminho onde a imagem será salva
                $caminhoUpload = '../uploads/';
                
                // Criar pasta se não existir
                if(!file_exists($caminhoUpload)) {
                    mkdir($caminhoUpload, 0777, true);
                }
                
                // Mover o arquivo
                if(move_uploaded_file($arquivoTmp, $caminhoUpload . $nomeImagem)) {
                    // Se for edição e tiver imagem antiga, deletar
                    if(!empty($_POST['id']) && !empty($data->imagem)) {
                        $caminhoAntigo = $caminhoUpload . $data->imagem;
                        if(file_exists($caminhoAntigo)) {
                            unlink($caminhoAntigo);
                        }
                    }
                } else {
                    $errors[] = "<li>Erro ao fazer upload da imagem</li>";
                }
            }
        } elseif(!empty($_POST['id']) && empty($_FILES['imagem']['name'])) {
            // Se for edição e não enviou nova imagem, manter a existente
            $nomeImagem = $data->imagem ?? '';
        }

        if(empty($errors)){
            
            // Prepara os dados para salvar
            $dadosParaSalvar = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao']
            ];
            
            // Adicionar imagem se tiver
            if(!empty($nomeImagem)) {
                $dadosParaSalvar['imagem'] = $nomeImagem;
            }

            // Se tiver ID preenchido no formulário, atualiza. Se não, cria um novo.
            if (!empty($_POST['id'])) {
                $dadosParaSalvar['id'] = $_POST['id'];
                $db->update($dadosParaSalvar);
                $success = "Artista atualizado com sucesso!";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Artista cadastrado com sucesso!";
            }
            
            // Redireciona após 2 segundos
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'ArtistaList.php';
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
    
    <form action="" method="post" enctype="multipart/form-data">
        <h3><?php echo !empty($data->id) ? 'Editar Artista' : 'Cadastro de Artista'; ?></h3>
        
        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

        <div class="row"> 
            <div class="col-12 mb-2">
                <label for="nome">Nome do Artista: </label>
                <input type="text" name="nome" class="form-control" value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>">
                <small class="text-muted">Ex: Ana Castela, Luan Santana, etc.</small>
            </div>
            
            <div class="col-12 mb-2">
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" class="form-control" rows="5"><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                <small class="text-muted">Biografia, história, principais sucessos, etc.</small>
            </div>

            <div class="col-12 mb-2">
                <label for="imagem">Foto do Artista: </label>
                <?php if(!empty($data->imagem)): ?>
                    <div class="mb-2">
                        <img src="../uploads/<?php echo $data->imagem; ?>" alt="Imagem atual" style="max-width: 150px; max-height: 150px; object-fit: cover;" class="img-thumbnail">
                        <small class="d-block text-muted">Imagem atual: <?php echo $data->imagem; ?></small>
                    </div>
                <?php endif; ?>
                <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP. Máximo: 5MB</small>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success"><?php echo !empty($data->id) ? 'Atualizar' : 'Cadastrar'; ?></button>
            <a href="ArtistaList.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>

<?php
include '../footer.php';
?>
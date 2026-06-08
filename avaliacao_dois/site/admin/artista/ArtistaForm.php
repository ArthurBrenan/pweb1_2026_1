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
                $success = "Artista atualizado com sucesso! Redirecionando...";
            } else {
                $db->store($dadosParaSalvar);
                $success = "Artista cadastrado com sucesso! Redirecionando...";
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

<style>
    body {
        background-color: #212529 !important;
    }
    /* Padronização de Inputs Dark */
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
    /* Estilo para a prévia da imagem do artista */
    .artist-preview {
        border: 2px solid #555;
        border-radius: 10px;
        background-color: #222;
        padding: 5px;
        max-width: 140px;
        max-height: 140px;
        object-fit: cover;
    }
</style>

<div style="background-color: #212529; min-height: 100vh; padding: 40px 20px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10 col-lg-8">
                
                <div style="background-color: #1a1a1a; border-radius: 20px; border: 1px solid #333; padding: 40px;">
                    
                    <div class="text-center mb-4">
                        <h1 style="color: #f1c40f; letter-spacing: 3px; font-size: 1.8rem; text-transform: uppercase; margin: 0;">
                            <?php echo !empty($data->id) ? 'EDITAR ARTISTA' : 'CADASTRO DE ARTISTA'; ?>
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
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

                        <div class="row"> 
                            <div class="col-12 mb-3">
                                <label for="nome" class="form-label form-label-gold">NOME DO ARTISTA</label>
                                <input type="text" name="nome" class="form-control form-dark" 
                                       value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                       placeholder="Ex: Jorge & Mateus, Beyoncé, Alok...">
                                <small style="color: #888; display: block; margin-top: 4px;">Nome artístico ou nome da banda.</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descricao" class="form-label form-label-gold">DESCRIÇÃO / BIOGRAFIA</label>
                                <textarea name="descricao" class="form-control form-dark" rows="5" 
                                          placeholder="Conte um pouco sobre a história, gênero musical e sucessos do artista..."><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                                <small style="color: #888; display: block; margin-top: 4px;">Informações complementares que aparecerão no perfil do artista.</small>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="imagem" class="form-label form-label-gold">FOTO DO ARTISTA</label>
                                
                                <?php if(!empty($data->imagem)): ?>
                                    <div class="mb-3 d-flex align-items-center gap-3" style="background-color: #252525; padding: 15px; border-radius: 12px; border: 1px solid #444;">
                                        <img src="../uploads/<?php echo $data->imagem; ?>" alt="Imagem atual" class="artist-preview">
                                        <div>
                                            <span class="badge bg-warning text-dark mb-1 fw-bold">Imagem Ativa</span>
                                            <small class="d-block" style="color: #bbb; word-break: break-all;"><?php echo $data->imagem; ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <input type="file" name="imagem" class="form-control form-dark" accept="image/jpeg,image/png,image/gif,image/webp">
                                <small style="color: #888; display: block; margin-top: 4px;">Formatos suportados: JPG, PNG, GIF, WEBP. Tamanho limite: 5MB</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2" style="border-radius: 30px; letter-spacing: 2px;">
                                <?php echo !empty($data->id) ? 'ATUALIZAR PERFIL' : 'CADASTRAR ARTISTA'; ?>
                            </button>
                            <div class="text-center mt-3">
                                <a href="ArtistaList.php" style="color: #888; text-decoration: none; font-size: 0.9rem;">
                                    &larr; Voltar para a <span style="color: #f1c40f;">Lista de Artistas</span>
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
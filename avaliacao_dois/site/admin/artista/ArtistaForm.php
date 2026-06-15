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
    
    /* File input */
    input[type="file"].form-control-custom {
        padding: 10px 15px;
        cursor: pointer;
    }
    
    input[type="file"].form-control-custom::-webkit-file-upload-button {
        background: #f1c40f;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        margin-right: 10px;
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
    
    /* Pré-visualização da imagem */
    .image-preview-container {
        background-color: #1a1a1a;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #2c2c2c;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .artist-preview {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #f1c40f;
    }
    
    .image-info {
        flex: 1;
    }
    
    .badge-active {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 5px;
    }
    
    .image-name {
        color: #a0a0a0;
        font-size: 0.75rem;
        word-break: break-all;
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
        
        .artist-preview {
            width: 60px;
            height: 60px;
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
                            <?php echo !empty($data->id) ? 'EDITAR ARTISTA' : 'CADASTRO DE ARTISTA'; ?>
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
                    <form action="" method="post" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : ''; ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label-custom">NOME DO ARTISTA</label>
                            <input type="text" name="nome" class="form-control-custom" 
                                   value="<?php echo isset($data->nome) ? htmlspecialchars($data->nome) : ''; ?>"
                                   placeholder="Ex: Tim Maia, Freddie Mercury, Amy Winehouse...">
                            <small class="helper-text">Nome artístico do artista ou banda.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label-custom">DESCRIÇÃO / BIOGRAFIA</label>
                            <textarea name="descricao" class="form-control-custom" rows="5" 
                                      placeholder="Conte um pouco sobre a história, gênero musical e sucessos do artista..."><?php echo isset($data->descricao) ? htmlspecialchars($data->descricao) : ''; ?></textarea>
                            <small class="helper-text">Informações complementares que aparecerão no perfil do artista.</small>
                        </div>

                        <div class="mb-4">
                            <label for="imagem" class="form-label-custom">FOTO DO ARTISTA</label>
                            
                            <?php if(!empty($data->imagem)): ?>
                                <div class="image-preview-container">
                                    <img src="../uploads/<?php echo $data->imagem; ?>" alt="Imagem atual" class="artist-preview">
                                    <div class="image-info">
                                        <div class="badge-active">Imagem Atual</div>
                                        <div class="image-name"><?php echo $data->imagem; ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" name="imagem" class="form-control-custom" accept="image/jpeg,image/png,image/gif,image/webp">
                            <small class="helper-text">Formatos suportados: JPG, PNG, GIF, WEBP. Tamanho limite: 5MB</small>
                        </div>

                        <button type="submit" class="btn-submit">
                            <?php echo !empty($data->id) ? 'ATUALIZAR ARTISTA' : 'CADASTRAR ARTISTA'; ?>
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="ArtistaList.php" class="back-link">
                                ← Voltar para a <span>Lista de Artistas</span>
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
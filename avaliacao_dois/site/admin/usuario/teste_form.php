<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "../db.class.php";

$mensagem = '';

if($_POST) {
    $db = new db('usuario');
    
    try {
        $dados = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'idade' => $_POST['idade'],
            'telefone' => $_POST['telefone'],
            'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
        ];
        
        $db->store($dados);
        $mensagem = "<p style='color:green'>✅ SALVOU COM SUCESSO!</p>";
        
    } catch(Exception $e) {
        $mensagem = "<p style='color:red'>❌ ERRO: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teste Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Formulário de Teste Simplificado</h1>
        
        <?php echo $mensagem; ?>
        
        <form method="post">
            <div class="mb-2">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Idade:</label>
                <input type="number" name="idade" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Telefone:</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            <div class="mb-2">
                <label>Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
</body>
</html>
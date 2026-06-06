<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "../db.class.php";

echo "<h1>Teste de Inserção Direta</h1>";

try {
    $db = new db('usuario');
    
    // Verificar se o email já existe
    $email = 'bruna.e2008@aluno.ifsc.edu.br';
    $existe = $db->findBy('email', $email);
    
    if($existe) {
        echo "<p style='color:orange'>⚠️ Email <strong>$email</strong> já está cadastrado!</p>";
        echo "<pre>";
        print_r($existe);
        echo "</pre>";
    } else {
        // Inserir novo usuário
        $dados = [
            'nome' => 'Bruna Emanueli Vaz de Oliveira',
            'email' => $email,
            'idade' => 18,
            'telefone' => '49989145162',
            'senha' => password_hash('123456', PASSWORD_DEFAULT)
        ];
        
        $db->store($dados);
        echo "<p style='color:green'>✅ Usuário inserido com sucesso!</p>";
    }
    
    // Listar todos os usuários
    echo "<h2>Usuários cadastrados:</h2>";
    $usuarios = $db->all();
    if(count($usuarios) > 0) {
        echo "<ul>";
        foreach($usuarios as $u) {
            echo "<li>ID: {$u->id} - Nome: {$u->nome} - Email: {$u->email}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum usuário cadastrado.</p>";
    }
    
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Erro: " . $e->getMessage() . "</p>";
}
?>
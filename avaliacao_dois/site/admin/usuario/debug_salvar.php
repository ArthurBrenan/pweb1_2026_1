<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug de Salvamento</h1>";

// Incluir a classe
include_once "../db.class.php";

try {
    // Dados de teste fixos (não vem do formulário)
    $dados_teste = [
        'nome' => 'Teste Debug',
        'email' => 'debug' . time() . '@teste.com', // email único com timestamp
        'idade' => 25,
        'telefone' => '999999999',
        'senha' => password_hash('123456', PASSWORD_DEFAULT)
    ];
    
    echo "<h2>Dados que serão salvos:</h2>";
    echo "<pre>";
    print_r($dados_teste);
    echo "</pre>";
    
    $db = new db('usuario');
    $resultado = $db->store($dados_teste);
    
    echo "<p style='color:green'>✅ Salvou com sucesso!</p>";
    
    // Mostrar todos os usuários
    $todos = $db->all();
    echo "<h2>Usuários no banco:</h2>";
    echo "<pre>";
    print_r($todos);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>❌ ERRO: " . $e->getMessage() . "</p>";
    echo "<h3>Stack trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
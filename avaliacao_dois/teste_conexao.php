<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once "site/admin/db.class.php";

try {
    $db = new db('usuario');
    echo "<h1 style='color:green'>✅ Conexão com o banco 'av2' estabelecida com sucesso!</h1>";
    
    // Tenta buscar todos os usuários
    $usuarios = $db->all();
    echo "<p>Total de usuários encontrados: " . count($usuarios) . "</p>";
    
} catch(Exception $e) {
    echo "<h1 style='color:red'>❌ Erro: " . $e->getMessage() . "</h1>";
}
?>
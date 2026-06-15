<?php
session_start();

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para comprar ingressos!']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

$ingressoDB = new db('ingresso');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantidade'])) {
    $id = (int)$_POST['id'];
    $quantidade = (int)$_POST['quantidade'];
    
    // Buscar o ingresso
    $ingresso = $ingressoDB->find($id);
    
    if (!$ingresso) {
        echo json_encode(['success' => false, 'message' => 'Ingresso não encontrado!']);
        exit;
    }
    
    if ($ingresso->quantidade < $quantidade) {
        echo json_encode(['success' => false, 'message' => 'Estoque insuficiente!']);
        exit;
    }
    
    // Atualizar o estoque
    $novoEstoque = $ingresso->quantidade - $quantidade;
    $resultado = $ingressoDB->update([
        'id' => $id,
        'quantidade' => $novoEstoque
    ]);
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Compra realizada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o estoque!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida!']);
}
?>
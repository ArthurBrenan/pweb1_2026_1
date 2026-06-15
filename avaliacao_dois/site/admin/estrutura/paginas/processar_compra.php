<?php
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Voce precisa estar logado!']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantidade'])) {
    $id = (int)$_POST['id'];
    $quantidade = (int)$_POST['quantidade'];
    
    if ($quantidade <= 0) {
        echo json_encode(['success' => false, 'message' => 'Quantidade invalida!']);
        exit;
    }
    
    $ingressoDB = new db('ingresso');
    $ingresso = $ingressoDB->find($id);
    
    if (!$ingresso) {
        echo json_encode(['success' => false, 'message' => 'Ingresso nao encontrado!']);
        exit;
    }
    
    if ($ingresso->quantidade < $quantidade) {
        echo json_encode(['success' => false, 'message' => 'Estoque insuficiente! Disponivel: ' . $ingresso->quantidade]);
        exit;
    }
    
    $novoEstoque = $ingresso->quantidade - $quantidade;
    
    // Tentativa 1: update com array associativo
    $resultado = $ingressoDB->update($id, ['quantidade' => $novoEstoque]);
    
    // Se falhou, tentar com objeto
    if (!$resultado) {
        $resultado = $ingressoDB->update((object)['id' => $id, 'quantidade' => $novoEstoque]);
    }
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Compra realizada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o estoque!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisicao invalida!']);
}
?>
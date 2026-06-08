<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function actionMessage($success, $actionError) {
    if (!empty($success)) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . $success . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }
    if (!empty($actionError)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $actionError . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }
}

function showValidationError($errors) {
    if (!empty($errors)) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errors as $error) {
            echo $error;
        }
        echo '</ul></div>';
    }
}

// FUNÇÕES ADICIONAIS PARA O LOGIN

function redirect($url) {
    header("Location: $url");
    exit;
}

// Verifica se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['usuario_id']);
}

// Se NÃO estiver logado, redireciona para o login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

// Se já estiver logado, redireciona para o index
function requireLogout() {
    if (isLoggedIn()) {
        redirect('index.php');
    }
}

// Pega o nome do usuário logado
function getUsuarioNome() {
    return $_SESSION['usuario_nome'] ?? 'Visitante';
}

// Pega o ID do usuário logado
function getUsuarioId() {
    return $_SESSION['usuario_id'] ?? null;
}
?>
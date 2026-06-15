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

// funcao p o login

function redirect($url) {
    header("Location: $url");
    exit;
}

// Verifica login
function isLoggedIn() {
    return isset($_SESSION['usuario_id']);
}

// Se nao login, redireciona p login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

// Se já login, redireciona p index
function requireLogout() {
    if (isLoggedIn()) {
        redirect('index.php');
    }
}

// Pega nome usuario
function getUsuarioNome() {
    return $_SESSION['usuario_nome'] ?? 'Visitante';
}

// Pega ID usuário 
function getUsuarioId() {
    return $_SESSION['usuario_id'] ?? null;
}
?>
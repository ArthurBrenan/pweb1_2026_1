<?php
session_start();

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
?>
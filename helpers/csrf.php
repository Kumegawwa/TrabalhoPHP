<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token_from_form = null) {
    if ($token_from_form === null && isset($_POST['csrf_token'])) {
        $token_from_form = $_POST['csrf_token'];
    } elseif ($token_from_form === null && isset($_GET['csrf_token'])) {
        $token_from_form = $_GET['csrf_token'];
    }


    if (empty($_SESSION['csrf_token']) || empty($token_from_form)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token_from_form);
}

function regenerateCsrfToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
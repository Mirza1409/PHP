<?php
function is_logged_in() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit;
    }
}

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>

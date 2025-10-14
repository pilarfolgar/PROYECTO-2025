<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function generar_nav_token($ttl_seconds = 10) {
    $token = bin2hex(random_bytes(16));
    $now = time();
    if (!isset($_SESSION['nav_tokens'])) $_SESSION['nav_tokens'] = [];
    $_SESSION['nav_tokens'][$token] = $now + $ttl_seconds;
    return $token;
}

function validar_nav_token($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($token) || !isset($_SESSION['nav_tokens'][$token])) return false;
    $expiry = $_SESSION['nav_tokens'][$token];
    unset($_SESSION['nav_tokens'][$token]); // un solo uso
    return $expiry >= time();
}

function limpiar_nav_tokens() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['nav_tokens'])) return;
    $now = time();
    foreach ($_SESSION['nav_tokens'] as $t => $expiry) {
        if ($expiry < $now) unset($_SESSION['nav_tokens'][$t]);
    }
}
?>

<?php
// nav_tokens.php
if (session_status() === PHP_SESSION_NONE) session_start();

function generar_nav_token($ttl_seconds = 300) {
    // crea token, lo guarda en session con expiracion
    $token = bin2hex(random_bytes(16));
    $now = time();
    if (!isset($_SESSION['nav_tokens'])) $_SESSION['nav_tokens'] = [];
    // guardamos token => expiry
    $_SESSION['nav_tokens'][$token] = $now + $ttl_seconds;
    return $token;
}

function validar_nav_token($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($token)) return false;
    if (!isset($_SESSION['nav_tokens'][$token])) return false;
    $expiry = $_SESSION['nav_tokens'][$token];
    // eliminar token (un solo uso)
    unset($_SESSION['nav_tokens'][$token]);
    if ($expiry < time()) return false;
    return true;
}

// limpieza periódica: elimina tokens expirados
function limpiar_nav_tokens() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['nav_tokens'])) return;
    $now = time();
    foreach ($_SESSION['nav_tokens'] as $t => $expiry) {
        if ($expiry < $now) unset($_SESSION['nav_tokens'][$t]);
    }
}
?>

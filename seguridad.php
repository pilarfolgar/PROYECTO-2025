<?php
session_start();

$tiempo_sesion = 1800; // 30 minutos

function redirigir_login() {
    header("Location: iniciosesion.php");
    exit;
}

// Verificar sesión activa
if (!isset($_SESSION['cedula'])) {
    if (isset($_COOKIE['token_usuario'])) {
        $token_cookie = $_COOKIE['token_usuario'];
        if (isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token_cookie)) {
            // Restaurar sesión
            $_SESSION['cedula'] = $_SESSION['cedula'];
            $_SESSION['usuario'] = $_SESSION['usuario'] ?? '';
            $_SESSION['rol'] = $_SESSION['rol'] ?? '';
            $_SESSION['acceso_panel'] = $_SESSION['acceso_panel'] ?? true;
        } else {
            redirigir_login();
        }
    } else {
        redirigir_login();
    }
}

// Expiración automática
if (isset($_SESSION['tiempo_inicio'])) {
    $duracion = time() - $_SESSION['tiempo_inicio'];
    if ($duracion > $tiempo_sesion) {
        session_unset();
        session_destroy();
        setcookie("token_usuario", "", time() - 3600, "/");
        redirigir_login();
    }
}
$_SESSION['tiempo_inicio'] = time();

<?php
session_start();

$tiempo_sesion = 1800; // 30 minutos

function redirigir_login() {
    header("Location: iniciosesion.php");
    exit();
}

// Validar sesión o token
if (!isset($_SESSION['cedula'])) {
    if (isset($_COOKIE['token_usuario']) && isset($_SESSION['token'])) {
        if (hash_equals($_SESSION['token'], $_COOKIE['token_usuario'])) {
            // Restaurar sesión
            $_SESSION['cedula'] = $_SESSION['cedula'] ?? '';
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

// Actualizar tiempo de sesión
$_SESSION['tiempo_inicio'] = time();

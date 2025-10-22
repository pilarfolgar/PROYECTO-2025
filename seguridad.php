<?php
session_start();

// Duración máxima de la sesión (en segundos)
$tiempo_sesion = 1800; // 30 minutos

// Función para redirigir al login
function redirigir_login() {
    header("Location: iniciosesion.php");
    exit;
}

// Verificar si hay sesión activa
if (!isset($_SESSION['cedula'])) {
    // Revisar si existe token en cookie
    if (isset($_COOKIE['token_usuario']) && isset($_SESSION['token'])) {
        $token_cookie = $_COOKIE['token_usuario'];
        if (hash_equals($_SESSION['token'], $token_cookie)) {
            // Restaurar sesión (opcional: recargar datos de BD si querés)
            $_SESSION['cedula'] = $_SESSION['cedula'] ?? '';
            $_SESSION['usuario'] = $_SESSION['usuario'] ?? '';
            $_SESSION['rol'] = $_SESSION['rol'] ?? '';
            $_SESSION['acceso_panel'] = $_SESSION['acceso_panel'] ?? true;
        } else {
            redirigir_login();
        }
    } else {
        // No hay sesión ni token
        redirigir_login();
    }
}

// Control de expiración automática
if (isset($_SESSION['tiempo_inicio'])) {
    $duracion = time() - $_SESSION['tiempo_inicio'];
    if ($duracion > $tiempo_sesion) {
        session_unset();
        session_destroy();
        setcookie("token_usuario", "", time() - 3600, "/");
        redirigir_login();
    }
}

// Actualizar timestamp de inicio de sesión
$_SESSION['tiempo_inicio'] = time();

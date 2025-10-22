<?php
session_start();

// Duración máxima de la sesión en segundos
$tiempo_sesion = 1800; // 30 minutos

// Función para redirigir al login
function redirigir_login() {
    header("Location: iniciosesion.php");
    exit();
}

// Validar sesión
if (!isset($_SESSION['cedula'])) {
    // No hay sesión activa
    redirigir_login();
}

// Expiración automática
if (isset($_SESSION['tiempo_inicio'])) {
    $duracion = time() - $_SESSION['tiempo_inicio'];
    if ($duracion > $tiempo_sesion) {
        session_unset();
        session_destroy();
        setcookie("token_usuario", "", time() - 3600, "/"); // borrar cookie si la hay
        redirigir_login();
    }
}

// Actualizar tiempo de inicio
$_SESSION['tiempo_inicio'] = time();

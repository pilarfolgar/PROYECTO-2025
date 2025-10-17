<?php
session_start();

// Tiempo máximo de inactividad (en segundos)
$tiempo_sesion = 1800; // 30 minutos

// Verificar si hay usuario logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit;
}

// Control de expiración por tiempo
if (isset($_SESSION['tiempo_inicio'])) {
    $duracion = time() - $_SESSION['tiempo_inicio'];
    if ($duracion > $tiempo_sesion) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit;
    }
}
$_SESSION['tiempo_inicio'] = time();

// Generar token de página único si no existe
if (!isset($_SESSION['page_token'])) {
    $_SESSION['page_token'] = bin2hex(random_bytes(16));
}

// Validar token GET opcional (solo si se usa)
if (isset($_GET['token']) && $_GET['token'] !== $_SESSION['page_token']) {
    session_unset();
    session_destroy();
    header("Location: iniciosesion.php");
    exit;
}

// Regenerar token para la siguiente carga
$_SESSION['page_token'] = bin2hex(random_bytes(16));
?>

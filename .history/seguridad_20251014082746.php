<?php
session_start();
require_once("nav_tokens.php");

// 1) usuario debe estar logueado
if (!isset($_SESSION['cedula']) || empty($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// 2) validar token de navegación
$token = $_GET['t'] ?? $_POST['t'] ?? null;
limpiar_nav_tokens();
if (!$token || !validar_nav_token($token)) {
    $_SESSION['mensaje'] = "Acceso directo no permitido. Por favor iniciá sesión nuevamente.";
    session_unset(); 
    session_destroy(); // opcional: fuerza re-login
    header("Location: iniciosesion.php");
    exit();
}
?>

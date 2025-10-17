<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Verifica que haya "acceso autorizado" desde login
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    // Si no hay acceso autorizado y tampoco hay una sesión iniciada, redirige
    if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== true) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit();
    }
}

// Si no existe esta variable, significa que es la primera carga tras login
if (!isset($_SESSION['sesion_iniciada'])) {
    $_SESSION['sesion_iniciada'] = true;
    // Consumimos acceso_panel solo la primera vez
    unset($_SESSION['acceso_panel']);
}
?>


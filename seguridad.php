<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Si no existe el flag de acceso desde login, pedimos login
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    // Si no hay el flag, significa que es un acceso directo desde link
    session_unset();
    session_destroy();
    header("Location: iniciosesion.php");
    exit();
}

// Consumir acceso_panel solo la primera vez
unset($_SESSION['acceso_panel']);

// Desde aquí en adelante, mientras dure la sesión, el usuario puede recargar
?>



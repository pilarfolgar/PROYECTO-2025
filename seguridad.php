<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Si no existe el token de sesión para esta pestaña, pedimos login
if (!isset($_SESSION['token_pestana'])) {
    // Generamos un token único por pestaña
    $_SESSION['token_pestana'] = bin2hex(random_bytes(16));
    
    // Solo si no viene desde login, pedimos login
    if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit();
    }
    
    // Consumimos el flag de acceso solo la primera vez
    unset($_SESSION['acceso_panel']);
}
?>



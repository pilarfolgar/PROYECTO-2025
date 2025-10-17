<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Si no existe la variable de “acceso verificado”, la verificamos
if (!isset($_SESSION['acceso_verificado'])) {
    // Solo la primera vez: validamos que venga desde login
    if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
        // No viene desde login → acceso directo → redirige
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit();
    }

    // Consumimos el flag de acceso_panel
    unset($_SESSION['acceso_panel']);

    // Marcamos que ya pasó la verificación
    $_SESSION['acceso_verificado'] = true;
}

// Desde aquí en adelante, mientras dure la sesión, el usuario puede recargar
?>




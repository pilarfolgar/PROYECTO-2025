<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Verifica que haya "acceso autorizado" desde login
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    // Opcional: destruir sesión para forzar re-login
    session_unset();
    session_destroy();
    header("Location: iniciosesion.php");
    exit();
}

// Consumir flag para que no se pueda usar el link directo otra vez
unset($_SESSION['acceso_panel']);
?>

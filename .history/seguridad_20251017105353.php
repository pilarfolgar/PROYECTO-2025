<?php
session_start();

// ============================
// 1️⃣ Verificar sesión activa
// ============================
if (!isset($_SESSION['cedula'])) {
    // No está logueado → redirige
    header("Location: iniciosesion.php");
    exit();
}

// ============================
// 2️⃣ Verificar acceso directo
// ============================

// Si aún no se ha validado el acceso
if (!isset($_SESSION['acceso_verificado'])) {

    // Comprobamos si viene desde login (flag de sesión temporal)
    if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
        // No viene del login → acceso directo
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

// ============================
// 3️⃣ Desde aquí, recargas F5
// ============================
// Mientras dure la sesión, el usuario puede navegar normalmente
?>

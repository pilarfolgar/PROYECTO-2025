<?php
// seguridad.php
session_start();

// Tiempo de vida de la sesión en segundos (opcional)
$tiempo_sesion = 10;

// Inicializar variable de control de navegación
if (!isset($_SESSION['navegando'])) {
    $_SESSION['navegando'] = true;
}

// Comprobar si el usuario está logueado
if (!isset($_SESSION['cedula'])) {
    // Si la sesión no tiene 'cedula', redirigir al login
    header("Location: iniciosesion.php");
    exit;
}

// Control para evitar que alguien copie la URL en otra pestaña
if (!isset($_SESSION['ultima_pagina'])) {
    $_SESSION['ultima_pagina'] = $_SERVER['REQUEST_URI'];
} else {
    $ultima = $_SESSION['ultima_pagina'];
    $actual = $_SERVER['REQUEST_URI'];

    // Si la URL actual es diferente a la anterior y no es un F5, reinicia sesión
    if ($actual !== $ultima && $_SERVER['REQUEST_METHOD'] === 'GET') {
        // Esto evita que F5 sobre la misma página pida login
        $_SESSION['ultima_pagina'] = $actual;
    }
}

// Actualizar tiempo de sesión para expiración automática
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
?>

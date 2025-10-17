<?php
session_start();

// Si no hay sesión iniciada, va al login
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Si no existe la marca de acceso autorizado, significa que abrieron la página directamente en otra pestaña
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    header("Location: iniciosesion.php");
    exit();
}

// Si es la primera vez que se accede a la página desde login, marcamos el acceso
if (!isset($_SESSION['pagina_actual'])) {
    $_SESSION['pagina_actual'] = $_SERVER['REQUEST_URI'];
}

// IMPORTANTE: no destruimos la sesión ni desunseteamos `acceso_panel`
// para que recargar la página no pida login
?>


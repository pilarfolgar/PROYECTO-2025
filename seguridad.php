<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Verifica que haya "acceso autorizado" desde login
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    // Si no hay acceso autorizado y no es una recarga de la misma sesión
    if (!isset($_SESSION['pagina_actual']) || $_SESSION['pagina_actual'] !== $_SERVER['REQUEST_URI']) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit();
    }
}

// Si no está seteada la página actual, la guardamos (primer acceso)
if (!isset($_SESSION['pagina_actual'])) {
    $_SESSION['pagina_actual'] = $_SERVER['REQUEST_URI'];
}

// Consumir acceso_panel solo en el primer acceso
if (isset($_SESSION['acceso_panel'])) {
    unset($_SESSION['acceso_panel']);
}
?>


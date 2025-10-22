<?php
session_start();

// Borrar variables si existen
if (isset($_SESSION['cedula'])) unset($_SESSION['cedula']);
if (isset($_SESSION['usuario'])) unset($_SESSION['usuario']);
if (isset($_SESSION['rol'])) unset($_SESSION['rol']);
if (isset($_SESSION['acceso_panel'])) unset($_SESSION['acceso_panel']);
if (isset($_SESSION['token'])) unset($_SESSION['token']);

// Destruir sesión
session_destroy();

// Borrar cookie de token
if (isset($_COOKIE['token_usuario'])) {
    setcookie("token_usuario", "", time() - 3600, "/");
}

// Redirigir al login
header("Location: iniciosesion.php");
exit();

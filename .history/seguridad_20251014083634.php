<?php
session_start();

// Solo permite acceso si hay sesión activa
if (!isset($_SESSION['cedula']) || empty($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}
?>

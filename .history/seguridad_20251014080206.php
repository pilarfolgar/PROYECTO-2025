<?php
session_start();

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['cedula']) || empty($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}
?>

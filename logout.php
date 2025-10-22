<?php
session_start();
if (isset($_SESSION['cedula'])) {
    unset($_SESSION['cedula']);
}
if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
}
session_destroy();
header("Location: iniciosesion.php");
exit();
?>
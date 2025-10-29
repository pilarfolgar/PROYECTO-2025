<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// Eliminar usuario
$stmt = $con->prepare("DELETE FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$stmt->close();

// Cerrar sesión
session_unset();
session_destroy();

// Redirigir a página de despedida o login
header("Location: goodbye.php");
exit;

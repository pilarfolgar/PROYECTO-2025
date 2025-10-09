<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = intval($_POST['cedula']);
    $sql = "UPDATE usuario SET verificado = 1 WHERE cedula = $cedula";
    if ($con->query($sql)) {
        $_SESSION['msg_usuario'] = 'aprobado';
    } else {
        $_SESSION['error_usuario'] = 'no_aprobado';
    }
}
header("Location: indexadministrativo.php");
exit;

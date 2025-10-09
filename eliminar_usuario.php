<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = intval($_POST['cedula']);
    $sql = "DELETE FROM usuario WHERE cedula = $cedula";
    if ($con->query($sql)) {
        $_SESSION['msg_usuario'] = 'eliminado';
    } else {
        $_SESSION['error_usuario'] = 'no_eliminado';
    }
}
header("Location: indexadministrativo.php");
exit;

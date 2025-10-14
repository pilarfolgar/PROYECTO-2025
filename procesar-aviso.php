<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $titulo  = $con->real_escape_string($_POST['titulo']);
    $mensaje = $con->real_escape_string($_POST['mensaje']);
    $fecha   = $_POST['fecha'] ?? date('Y-m-d');

    if($titulo && $mensaje){
        $sql = "INSERT INTO avisos (titulo, mensaje, fecha) VALUES ('$titulo', '$mensaje', '$fecha')";
        if($con->query($sql)){
            $_SESSION['msg_aviso'] = true;
        } else {
            $_SESSION['error_aviso'] = "Error al guardar el aviso";
        }
    } else {
        $_SESSION['error_aviso'] = "Debe completar todos los campos";
    }
}
header("Location: indexadministrativo.php");
exit;
?>

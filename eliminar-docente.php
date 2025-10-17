<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula = $_GET['cedula'] ?? null;
if($cedula){
    $stmt = $con->prepare("DELETE FROM usuario WHERE cedula=?");
    $stmt->bind_param("i",$cedula);
    $stmt->execute();
}

header("Location: indexadministrativoDatos.php");
exit();
?>

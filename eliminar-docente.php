<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula = $_GET['cedula'] ?? null;
if ($cedula) {
    $stmt = $con->prepare("DELETE FROM usuario WHERE cedula=?");
    $stmt->bind_param("i", $cedula);
    if ($stmt->execute()) {
        $_SESSION['msg_docente'] = "Docente eliminado correctamente 🗑️";
    } else {
        $_SESSION['error_docente'] = "Error al eliminar docente: " . $stmt->error;
    }
}
header("Location: indexadministrativoDatos.php");
exit();
?>


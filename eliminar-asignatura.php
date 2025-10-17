<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "DELETE FROM asignatura WHERE id_asignatura = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['msg_asignatura'] = "Asignatura eliminada correctamente 🗑️";
    } else {
        $_SESSION['error_asignatura'] = "Error al eliminar asignatura: " . $stmt->error;
    }
}
header("Location: indexadministrativoDatos.php");
exit();
?>

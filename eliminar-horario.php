<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "DELETE FROM horarios WHERE id_horario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['msg_horario'] = "Horario eliminado correctamente 🗑️";
    } else {
        $_SESSION['error_horario'] = "Error al eliminar horario: " . $stmt->error;
    }
}
header("Location: indexadministrativoDatos.php");
exit();
?>

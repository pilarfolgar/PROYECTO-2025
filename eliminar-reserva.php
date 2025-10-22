<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Eliminar reserva
$stmt = $con->prepare("DELETE FROM reserva WHERE id_reserva = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['msg_reserva'] = "Reserva eliminada con éxito ✅";
} else {
    $_SESSION['error_reserva'] = "Error al eliminar la reserva: " . $stmt->error;
}

header("Location: indexadministrativoDatos.php");
exit();
?>

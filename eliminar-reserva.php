<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (!isset($_GET['id'])) {
    $_SESSION['error_reserva'] = "ID de reserva no especificado";
    header("Location: index-administrativo-datos.php");
    exit;
}

$id_reserva = intval($_GET['id']);

$sql = "DELETE FROM reservas WHERE id_reserva = $id_reserva";
if ($con->query($sql)) {
    $_SESSION['msg_reserva'] = "Reserva eliminada correctamente";
} else {
    $_SESSION['error_reserva'] = "Error al eliminar la reserva: " . $con->error;
}

header("Location: index-administrativo-datos.php");
exit;
?>

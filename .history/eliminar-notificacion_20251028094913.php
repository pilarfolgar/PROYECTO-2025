<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$cedula_actual = $_SESSION['cedula'];
$id = $_GET['id'] ?? null;

$sql = "SELECT id FROM notificaciones WHERE id = ? AND adscripto_cedula = ? AND rol_emisor = 'administrativo'";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $id, $cedula_actual); // CORREGIDO: "ii"
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('🚫 No tienes permiso para eliminar esta notificación.');window.location='indexadministrativo.php';</script>";
    exit();
}

$del = $con->prepare("DELETE FROM notificaciones WHERE id = ? AND adscripto_cedula = ? AND rol_emisor = 'administrativo'");
$del->bind_param("ii", $id, $cedula_actual); // CORREGIDO: "ii"

if ($del->execute()) {
    echo "<script>alert('🗑️ Notificación eliminada correctamente.');window.location='indexadministrativo.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar la notificación.');window.location='indexadministrativo.php';</script>";
}
?>

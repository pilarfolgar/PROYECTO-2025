<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Validar sesión y permisos
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'adscripto') {
    header("Location: login.php");
    exit();
}

$cedula_actual = $_SESSION['cedula'];
$id = $_GET['id'] ?? null;

// Verificar que la notificación pertenezca al adscripto actual
$sql = "SELECT id FROM notificaciones WHERE id = ? AND docente_cedula = ? AND rol_emisor = 'adscripto'";
$stmt = $con->prepare($sql);
$stmt->bind_param("is", $id, $cedula_actual);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('🚫 No tienes permiso para eliminar esta notificación.');window.location='indexadministrativo.php';</script>";
    exit();
}

// Eliminar la notificación
$del = $con->prepare("DELETE FROM notificaciones WHERE id = ? AND docente_cedula = ? AND rol_emisor = 'adscripto'");
$del->bind_param("is", $id, $cedula_actual);

if ($del->execute()) {
    echo "<script>alert('🗑️ Notificación eliminada correctamente.');window.location='indexadministrativo.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar la notificación.');window.location='indexadministrativo.php';</script>";
}
?>


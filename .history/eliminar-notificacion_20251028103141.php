<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión de administrativo
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$cedula_admin = $_SESSION['cedula'];
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID de notificación inválido.');window.location='indexadministrativo.php';</script>";
    exit();
}

// 1️⃣ Eliminar notificación por expiración (más de 7 días)
$sql_expira = "DELETE FROM notificaciones WHERE fecha_expiracion IS NOT NULL AND fecha_expiracion < NOW()";
$con->query($sql_expira);

// 2️⃣ Verificar que la notificación exista y sea del administrativo
$sql = "SELECT id FROM notificaciones WHERE id = ? AND docente_cedula = ? AND rol_emisor = 'administrativo'";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $id, $cedula_admin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('🚫 No tienes permiso para eliminar esta notificación o no existe.');window.location='indexadministrativo.php';</script>";
    exit();
}

// 3️⃣ Eliminar notificación
$del = $con->prepare("DELETE FROM notificaciones WHERE id = ?");
$del->bind_param("i", $id);

if ($del->execute()) {
    echo "<script>alert('🗑️ Notificación eliminada correctamente para todos los usuarios.');window.location='indexadministrativo.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar la notificación.');window.location='indexadministrativo.php';</script>";
}
?>

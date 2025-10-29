<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Solo administrativos
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$cedula_admin = $_SESSION['cedula'];
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido.');window.location='indexadministrativo.php';</script>";
    exit();
}

// ================================
// 1️⃣ Verificar que la notificación exista y sea del admin (rol_emisor = 'administrativo')
// ================================
$sqlCheck = "SELECT id, id_grupo FROM notificaciones WHERE id = ? AND rol_emisor = 'administrativo'";
$stmtCheck = $con->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('🚫 No tienes permiso para eliminar esta notificación.');window.location='indexadministrativo.php';</script>";
    exit();
}

$noti = $result->fetch_assoc();
$id_grupo = $noti['id_grupo'];
$stmtCheck->close();

// ================================
// 2️⃣ Eliminar la notificación para todos (estudiantes del grupo)
// ================================
$del = $con->prepare("DELETE FROM notificaciones WHERE id = ? OR id_grupo = ?");
$del->bind_param("ii", $id, $id_grupo);

if ($del->execute()) {
    echo "<script>alert('🗑️ Notificación eliminada correctamente para todos los usuarios del grupo.');window.location='indexadministrativo.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar la notificación.');window.location='indexadministrativo.php';</script>";
}

// ================================
// 3️⃣ Borrar automáticamente notificaciones expiradas (>7 días)
// ================================
$autoDel = $con->prepare("DELETE FROM notificaciones WHERE fecha_expiracion IS NOT NULL AND fecha_expiracion < NOW()");
$autoDel->execute();
$autoDel->close();

$con->close();
?>

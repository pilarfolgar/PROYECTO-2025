<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión de administrativo
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID de notificación inválido.');window.location='indexadministrativo.php';</script>";
    exit();
}

// 1️⃣ Eliminar notificaciones expiradas automáticamente
$sql_expira = "DELETE FROM notificaciones WHERE fecha_expiracion IS NOT NULL AND fecha_expiracion < NOW()";
$con->query($sql_expira);

// 2️⃣ Verificar que la notificación exista
$sql_verif = "SELECT id FROM notificaciones WHERE id = ?";
$stmt_verif = $con->prepare($sql_verif);
$stmt_verif->bind_param("i", $id);
$stmt_verif->execute();
$result = $stmt_verif->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('🚫 No existe la notificación.');window.location='indexadministrativo.php';</script>";
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

// Cerrar conexiones
$stmt_verif->close();
$del->close();
$con->close();
?>

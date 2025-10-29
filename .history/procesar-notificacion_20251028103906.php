<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Solo administrativo puede enviar notificaciones
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$cedula_admin = $_SESSION['cedula'];

// Validar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (!$id_grupo || !$titulo || !$mensaje) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');window.history.back();</script>";
        exit();
    }

    // Fecha de expiración: 7 días desde hoy
    $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+7 days'));

    // Insertar notificación
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, rol_emisor, fecha_expiracion) 
            VALUES (?, ?, ?, ?, NOW(), 'administrativo', ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iisss", $id_grupo, $cedula_admin, $titulo, $mensaje, $fecha_expiracion);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Notificación enviada correctamente.');window.location='indexadministrativo.php';</script>";
    } else {
        echo "<script>alert('❌ Error al enviar la notificación.');window.history.back();</script>";
    }
    $stmt->close();
    $con->close();
} else {
    header("Location: indexadministrativo.php");
    exit();
}
?>

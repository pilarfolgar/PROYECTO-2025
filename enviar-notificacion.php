<?php
// Mostrar errores para debug (temporal)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

// Verificar conexión
if (!$con) {
    echo json_encode(["success" => false, "message" => "Error al conectar la base de datos"]);
    exit;
}

// Recibir datos POST
$id_grupo = intval($_POST['id_grupo'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');
$cedula_docente = $_SESSION['cedula'] ?? '';

// Validar datos
if (!$id_grupo || !$titulo || !$mensaje || !$cedula_docente) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos: id_grupo=$id_grupo, titulo=$titulo, mensaje=$mensaje, cedula=$cedula_docente"
    ]);
    exit;
}

// Preparar INSERT correctamente con los nombres de columnas exactos
$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
        VALUES (?, ?, ?, ?, NOW(), 0, 0, 'docente')";

$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error prepare: " . $con->error]);
    exit;
}

$stmt->bind_param("isss", $id_grupo, $cedula_docente, $titulo, $mensaje);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Notificación enviada correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error execute: " . $stmt->error]);
}

$stmt->close();
$con->close();
?>

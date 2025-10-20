<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$docente_cedula = $_SESSION['cedula'] ?? 0;

if(!$docente_cedula){
    echo json_encode(['ok'=>false, 'error'=>'No se ha iniciado sesión']);
    exit;
}

// Recibimos datos del POST
$id_grupo = intval($_POST['id_grupo'] ?? 0);
$titulo    = trim($_POST['titulo'] ?? '');
$mensaje   = trim($_POST['mensaje'] ?? '');

if(!$id_grupo || !$titulo || !$mensaje){
    echo json_encode(['ok'=>false, 'error'=>'Datos incompletos']);
    exit;
}

// Aquí va tu código de insertar la notificación
$sql = "INSERT INTO notificaciones (id_grupo, titulo, mensaje, rol_emisor) VALUES (?, ?, ?, 'docente')";
$stmt = $con->prepare($sql);
$stmt->bind_param("iss", $id_grupo, $titulo, $mensaje);
if($stmt->execute()){
    echo json_encode(['ok'=>true]);
} else {
    echo json_encode(['ok'=>false, 'error'=>'Error al guardar notificación']);
}
$stmt->close();
$con->close();
?>


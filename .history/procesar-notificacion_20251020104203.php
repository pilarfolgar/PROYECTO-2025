<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

// Validar datos
if(!$id_grupo || !$titulo || !$mensaje || !$docente_cedula){
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
    header("Location: indexdocente.php");
    exit();
}

// Preparar valores
$fecha = date("Y-m-d H:i:s");
$visto_estudiante = 0;
$visto_adscripto  = 0;
$rol_emisor = 'docente'; // cadena VARCHAR

// Insertar notificación
$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
if(!$stmt) die("Error prepare: ".$con->error);

// Cambiamos 'i' por 's' para rol_emisor
$stmt->bind_param("iissiiis", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto, $rol_emisor);

if($stmt->execute()){
    $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
} else {
    $_SESSION['error_notificacion'] = "Ocurrió un error al enviar la notificación: ".$stmt->error;
}

$stmt->close();
$con->close();

// Redirigir al panel docente
header("Location: indexdocente.php");
exit();

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
    die("Faltan datos obligatorios. Datos recibidos: " . json_encode($_POST));
}

// Preparar valores
$fecha = date("Y-m-d H:i:s");
$visto_estudiante = 0;
$visto_adscripto  = 0;
$rol_emisor = 'docente'; // cadena VARCHAR

// Debug: mostrar los valores antes de insertar
// echo "DEBUG: ", json_encode([$id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto, $rol_emisor]);

$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
if(!$stmt){
    die("Error al preparar la consulta: ".$con->error);
}

$stmt->bind_param("iissiiis", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto, $rol_emisor);

// Ejecutar y chequear error
if(!$stmt->execute()){
    die("Error al ejecutar la consulta: ".$stmt->error);
}

$stmt->close();
$con->close();

$_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
header("Location: indexdocente.php");
exit();

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

// Validación básica
if(!$id_grupo || !$titulo || !$mensaje || !$docente_cedula){
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
    header("Location: indexdocente.php");
    exit();
}

// Insertar notificación
$fecha = date("Y-m-d H:i:s");
$visto_estudiante = 0;
$visto_adscripto = 0;

$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
if(!$stmt){
    $_SESSION['error_notificacion'] = "Error prepare: ".$con->error;
    header("Location: indexdocente.php");
    exit();
}

$stmt->bind_param("iissiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto);

if(!$stmt->execute()){
    $_SESSION['error_notificacion'] = "Error execute: ".$stmt->error;
    header("Location: indexdocente.php");
    exit();
}

$stmt->close();
$con->close();

$_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
header("Location: indexdocente.php");
exit();

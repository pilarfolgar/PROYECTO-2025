<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

// Determinar rol emisor
$rol = $_SESSION['rol'] ?? ''; // 'docente' o 'administrativo' (adscripto)
$cedula_emisor = $_SESSION['cedula'] ?? 0;

// Inicializar cédula de adscripto
$adscripto_cedula = ($rol === 'administrativo') ? $cedula_emisor : null; // solo se llena si es adscripto

if($id_grupo && $titulo && $mensaje && $cedula_emisor){
    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = ($rol == 'docente') ? 0 : 1; // si lo envía adscripto, estudiante ya lo ve como leído
    $visto_adscripto  = ($rol == 'administrativo') ? 0 : 1; // si lo envía docente, adscripto lo ve como no leído

    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, adscripto_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if(!$stmt) die("Error prepare notificaciones: ".$con->error);

    $stmt->bind_param(
        "ii ss s iii", 
        $id_grupo,
        $cedula_emisor,
        $adscripto_cedula,
        $titulo,
        $mensaje,
        $fecha,
        $visto_estudiante,
        $visto_adscripto
    );

    if(!$stmt->execute()) die("Error execute: ".$stmt->error);
    $stmt->close();

    $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
} else {
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
}

// Redirigir según rol
if($rol == 'docente'){
    header("Location: indexdocente.php");
}else{
    header("Location: indexadministrativo.php");
}
exit();

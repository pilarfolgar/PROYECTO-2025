<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

$rol = $_SESSION['rol'] ?? ''; // 'docente' o 'administrativo'
$cedula_emisor = $_SESSION['cedula'] ?? 0;

if($id_grupo && $titulo && $mensaje && $cedula_emisor){
    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = ($rol == 'docente') ? 0 : 1;
    $visto_adscripto  = ($rol == 'administrativo') ? 0 : 1;

    // Si es administrativo, no hay docente_cedula
    $docente_cedula = ($rol == 'docente') ? $cedula_emisor : NULL;

    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "iissiiis",
        $id_grupo,
        $docente_cedula,
        $titulo,
        $mensaje,
        $fecha,
        $visto_estudiante,
        $visto_adscripto,
        $rol
    );
    if(!$stmt->execute()) die("Error execute: ".$stmt->error);
    $stmt->close();

    $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";
} else {
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
}

if($rol == 'docente'){
    header("Location: indexdocente.php");
}else{
    header("Location: indexadministrativo.php");
}
exit();

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Datos del formulario
$id_grupo = $_POST['id_grupo'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';

// Identificar el rol del emisor
$rol_emisor = $_SESSION['rol'] ?? 'estudiante'; // 'docente', 'adscriptor', 'estudiante'

// Para docentes, registrar su cédula
$docente_cedula = ($rol_emisor === 'docente') ? $_SESSION['cedula'] : null;

// Validación básica
if(!$id_grupo || !$titulo || !$mensaje){
    $_SESSION['error_notificacion'] = true;
    header("Location: indexadministrativo.php");
    exit();
}

// Insertar notificación
$sql = "INSERT INTO notificaciones (id_grupo, docente_cedula, titulo, mensaje, rol_emisor)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("iisss", $id_grupo, $docente_cedula, $titulo, $mensaje, $rol_emisor);

if($stmt->execute()){
    $_SESSION['msg_notificacion'] = true;
}else{
    $_SESSION['error_notificacion'] = true;
}

$stmt->close();
$con->close();

header("Location: indexadministrativo.php");
exit();

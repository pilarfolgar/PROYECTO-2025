<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recibir datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$docente_id = $_SESSION['usuario_cedula'] ?? 0;

if($id_grupo && $titulo && $mensaje){
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_id, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, CURDATE(), 0, 0)";
    $stmt = $con->prepare($sql);
    if(!$stmt){
        die("Error en prepare: " . $con->error);
    }
    $stmt->bind_param("iiss", $id_grupo, $docente_id, $titulo, $mensaje);
    if($stmt->execute()){
        $_SESSION['msg_notificacion'] = 'enviada';
    } else {
        $_SESSION['error_notificacion'] = 'error: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['error_notificacion'] = 'faltan_datos';
}

header("Location: indexadministrativo.php");
exit();
?>

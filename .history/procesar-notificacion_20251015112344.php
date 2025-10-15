<?php 
session_start();
require("conexion.php");
$con = conectar_bd();

$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

if($id_grupo && $titulo && $mensaje && $docente_cedula){

    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = 0; // Inicialmente no leído
    $visto_adscripto  = 0;

    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if(!$stmt) die("Error prepare notificaciones: ".$con->error);

    if(!$stmt->bind_param("iissiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto))
        die("Error bind_param: ".$stmt->error);

    if(!$stmt->execute()) die("Error execute: ".$stmt->error);

    $stmt->close();
    $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";

} else {
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
}

header("Location: indexadministrativo.php");
exit();
?>

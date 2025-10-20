<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$docente = $_SESSION['cedula'] ?? '';
$id_grupo = intval($_POST['id_grupo'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if(!$docente || !$id_grupo || !$titulo || !$mensaje){
    echo json_encode(['ok'=>false, 'error'=>'Datos incompletos']);
    exit;
}

$fecha = date('Y-m-d H:i:s');
$visto_estudiante = 0;
$visto_adscripto = 0;

$sql = "INSERT INTO notificaciones (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("issssii", $id_grupo, $docente, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto);
if($stmt->execute()){
    echo json_encode(['ok'=>true]);
} else {
    echo json_encode(['ok'=>false, 'error'=>$stmt->error]);
}
$stmt->close();
?>


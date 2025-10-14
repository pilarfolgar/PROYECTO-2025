<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recibir datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

// Usar la cédula del docente desde la sesión
$docente_cedula = $_SESSION['cedula'] ?? 0;

// Validar que se recibieron todos los datos
if($id_grupo && $titulo && $mensaje && $docente_cedula){

    // Preparar SQL
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    if(!$stmt){
        die("Error en prepare: " . $con->error);
    }

    // Valores a insertar
    $fecha = date("Y-m-d");
    $visto_estudiante = 0;
    $visto_adscripto  = 0;

    // Bind de parámetros: i = integer, s = string
    $stmt->bind_param("isssiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto);

    // Ejecutar
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


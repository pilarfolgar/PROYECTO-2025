<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// 1️⃣ Recibir datos del formulario
$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

// 2️⃣ Cédula del docente desde la sesión
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

// 3️⃣ Validar datos
if($id_grupo && $titulo && $mensaje && $docente_cedula){
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    if(!$stmt){
        $_SESSION['error_notificacion'] = "Error en prepare: ".$con->error;
        header("Location: indexadministrativo.php");
        exit();
    }

    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = 0;
    $visto_adscripto  = 0;

    $stmt->bind_param("isssiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto);

    if($stmt->execute()){
        $_SESSION['msg_notificacion'] = "enviada";
    } else {
        $_SESSION['error_notificacion'] = "Error al enviar notificación: ".$stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error_notificacion'] = "faltan_datos";
}

// Redirigir al panel administrativo
header("Location: indexadministrativo.php");
exit();
?>

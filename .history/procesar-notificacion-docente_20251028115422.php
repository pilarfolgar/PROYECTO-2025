<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión
if(!isset($_SESSION['rol'], $_SESSION['cedula']) || $_SESSION['rol'] !== 'docente'){
    $_SESSION['error_notificacion'] = true;
    header("Location: index.php"); // Cambiar a la página de login o panel docente
    exit();
}

// Recibir datos del formulario
$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
$rol_emisor = $_SESSION['rol'];
$cedula_emisor = $_SESSION['cedula'];

// Validar campos
if($id_grupo <= 0 || empty($titulo) || empty($mensaje)){
    $_SESSION['error_notificacion'] = true;
    header("Location: indexdocente.php");
    exit();
}

// Insertar notificación
$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
        VALUES (?, ?, ?, ?, NOW(), 0, 0, ?)";

$stmt = $con->prepare($sql);
if(!$stmt){
    die("Error en la preparación de la consulta: " . $con->error);
}

$stmt->bind_param("iisss", $id_grupo, $cedula_emisor, $titulo, $mensaje, $rol_emisor);

if($stmt->execute()){
    $_SESSION['msg_notificacion'] = true;
} else {
    $_SESSION['error_notificacion'] = true;
}

// Cerrar conexiones
$stmt->close();
$con->close();

// Redirigir al panel docente
header("Location: indexdocente.php");
exit();
?>

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar que haya sesión iniciada
if(!isset($_SESSION['rol'], $_SESSION['cedula'])){
    $_SESSION['error_notificacion'] = true;
    header("Location: indexadministrativo.php");
    exit();
}

// Recibir y limpiar datos del formulario
$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
$rol_emisor = $_SESSION['rol'];
$cedula_emisor = $_SESSION['cedula'];

// Validar datos
if($id_grupo <= 0 || empty($titulo) || empty($mensaje)){
    $_SESSION['error_notificacion'] = true;
    header("Location: indexadministrativo.php");
    exit();
}

// Insertar notificación en la base de datos
$sql = "INSERT INTO notificaciones 
        (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto, rol_emisor)
        VALUES (?, ?, ?, ?, NOW(), 0, 0, ?)";

$stmt = $con->prepare($sql);
if(!$stmt){
    die("Error en la preparación de la consulta: " . $con->error);
}

$stmt->bind_param("iisss", $id_grupo, $cedula_emisor, $titulo, $mensaje, $rol_emisor);

// Ejecutar y manejar resultado
if($stmt->execute()){
    $_SESSION['msg_notificacion'] = true;
} else {
    $_SESSION['error_notificacion'] = true;
}

// Cerrar conexiones
$stmt->close();
$con->close();

// Redirigir al panel administrativo
header("Location: indexadministrativo.php");
exit();
?>

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar que haya sesión y sea docente
if(!isset($_SESSION['cedula']) || $_SESSION['rol'] != 'docente'){
    header("Location: index.php");
    exit;
}

$docente_cedula = (int)$_SESSION['cedula'];  // convertimos a int
$aula = $_POST['aula'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';

if(empty($aula) || empty($fecha) || empty($hora_inicio) || empty($hora_fin)){
    die("Todos los campos son obligatorios.");
}

// Verificar solapamiento de reservas
$sql_check = "SELECT * FROM reservas_aulas 
              WHERE aula = ? AND fecha = ? 
              AND ((hora_inicio <= ? AND hora_fin > ?) 
                   OR (hora_inicio < ? AND hora_fin >= ?))";
$stmt = $con->prepare($sql_check);
$stmt->bind_param("isssss", $aula, $fecha, $hora_inicio, $hora_inicio, $hora_fin, $hora_fin);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    die("Error: El aula ya está reservada en ese horario.");
}

// Insertar reserva
$sql_insert = "INSERT INTO reservas_aulas (docente_cedula, aula, fecha, hora_inicio, hora_fin)
               VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql_insert);
$stmt->bind_param("issss", $docente_cedula, $aula, $fecha, $hora_inicio, $hora_fin);

if($stmt->execute()){
    echo "<p>Reserva realizada correctamente.</p><a href='indexdocentes.php'>Volver</a>";
}else{
    echo "Error al guardar la reserva: " . $con->error;
}
?>

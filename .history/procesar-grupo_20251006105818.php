<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Recoger datos del formulario
$nombre = $_POST['nombre'];
$orientacion = $_POST['orientacion'];
$cantidad = (int)$_POST['cantidad'];
$asignaturas = $_POST['asignaturas'] ?? []; // array de asignaturas
$horarios = $_POST['horarios'] ?? [];       // array de horarios

// Insertar grupo
$stmt = $con->prepare("INSERT INTO grupo (nombre, orientacion, cantidad_estudiantes) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $nombre, $orientacion, $cantidad);
$stmt->execute();
$id_grupo = $stmt->insert_id;
$stmt->close();

// Relacionar grupo con asignaturas
foreach($asignaturas as $id_asignatura){
    $stmt = $con->prepare("INSERT INTO grupo_asignatura (id_grupo, id_asignatura) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_grupo, $id_asignatura);
    $stmt->execute();
    $stmt->close();
}

// Relacionar grupo con horarios
foreach($horarios as $id_horario){
    $stmt = $con->prepare("INSERT INTO grupo_horario (id_grupo, id_horario) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_grupo, $id_horario);
    $stmt->execute();
    $stmt->close();
}

$_SESSION['msg_grupo'] = 'guardado';
header("Location: indexadministrativo.php");
exit();
?>

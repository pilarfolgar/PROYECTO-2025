<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar que el estudiante haya iniciado sesión
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'estudiante'){
    header("Location: iniciosesion.php");
    exit;
}

$estudiante_id = $_SESSION['usuario_id'];
$grupo_id = $_SESSION['grupo_id'];

// ====== NOTIFICACIONES DEL ESTUDIANTE ======
$notificaciones = [];
$sql_notif = "SELECT id, titulo, mensaje, fecha, visto_estudiante 
              FROM notificaciones 
              WHERE grupo_id = ? 
              ORDER BY fecha DESC";
$stmt = $con->prepare($sql_notif);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $notificaciones[] = $row;
}

// ====== AVISOS GENERALES ======
$avisos = [];
$sql_avisos = "SELECT titulo, mensaje, fecha FROM avisos ORDER BY fecha DESC";
$result = $con->query($sql_avisos);
while($row = $result->fetch_assoc()){
    $avisos[] = $row;
}

// ====== HORARIOS DEL GRUPO ======
$horarios = [];
$sql_horarios = "SELECT h.dia_semana, a.nombre AS asignatura, h.hora_inicio, h.hora_fin, h.aula
                 FROM horarios h
                 INNER JOIN asignaturas a ON h.id_asignatura = a.id
                 WHERE h.grupo_id = ?
                 ORDER BY FIELD(dia_semana,'Lunes','Martes','Miércoles','Jueves','Viernes'), h.hora_inicio";
$stmt = $con->prepare($sql_horarios);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $horarios[] = $row;
}
?>

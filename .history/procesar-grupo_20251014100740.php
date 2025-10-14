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

// ============================
// VERIFICAR SI EL GRUPO YA EXISTE
// ============================
$sql_check = "SELECT * FROM grupo WHERE nombre = ? AND orientacion = ?";
$stmt_check = $con->prepare($sql_check);
$stmt_check->bind_param("ss", $nombre, $orientacion);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if($result_check->num_rows > 0){
    // Grupo repetido
    $_SESSION['error_grupo'] = 'repetido';
    $stmt_check->close();
    header("Location: indexadministrativo.php");
    exit();
}
$stmt_check->close();

// ============================
// INSERTAR GRUPO
// ============================
$stmt = $con->prepare("INSERT INTO grupo (nombre, orientacion, cantidad_estudiantes) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $nombre, $orientacion, $cantidad);
if($stmt->execute()){
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

    $_SESSION['msg_grupo'] = 'guardado'; // Éxito
} else {
    $_SESSION['error_grupo'] = 'error'; // Error al guardar
}

header("Location: indexadministrativo.php");
exit();
?>

<?php
require("conexion.php");
$con = conectar_bd();

$id_curso = intval($_GET['id_curso']);
$sql = "SELECT id_asignatura, nombre FROM asignatura WHERE id_curso = $id_curso ORDER BY nombre";
$result = $con->query($sql);

$asignaturas = [];
while($row = $result->fetch_assoc()){
    $asignaturas[] = $row;
}

header('Content-Type: application/json');
echo json_encode($asignaturas);
?>

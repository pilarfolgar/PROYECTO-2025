<?php
require("conexion.php");
$con = conectar_bd();

$id_curso = intval($_GET['id_curso'] ?? 0);
$data = [];

if($id_curso){
    $stmt = $con->prepare("SELECT a.id_asignatura, a.nombre 
                           FROM asignatura a
                           JOIN curso_asignatura ca ON a.id_asignatura = ca.id_asignatura
                           WHERE ca.id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($data);

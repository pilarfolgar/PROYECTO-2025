<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Opcional: verificar que sea administrativo
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrativo'){
    http_response_code(403);
    echo json_encode([]);
    exit();
}

// Traer los reportes más recientes (por ejemplo últimos 20)
$sql = "SELECT nombre, email, objeto, descripcion, fecha, creado_en FROM reportes ORDER BY creado_en DESC LIMIT 20";
$result = $con->query($sql);

$reportes = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $reportes[] = $row;
    }
}

echo json_encode($reportes);
?>

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if(isset($_GET['id']) && isset($_SESSION['rol']) && $_SESSION['rol'] == 'estudiante'){
    $id = intval($_GET['id']);
    $sql = "UPDATE notificaciones SET visto_estudiante = 1 WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}
?>


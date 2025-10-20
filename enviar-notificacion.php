<?php
require("seguridad.php");
require("conexion.php");

$con = conectar_bd();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $cedula_docente = $_SESSION['cedula'];

    if($id_grupo && $titulo && $mensaje){
        $sql = "INSERT INTO notificaciones (id_grupo, cedula_docente, titulo, mensaje, fecha) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isss", $id_grupo, $cedula_docente, $titulo, $mensaje);
        if($stmt->execute()){
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar en la base de datos"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Faltan datos requeridos"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>

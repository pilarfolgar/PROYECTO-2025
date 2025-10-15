<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id_grupo = $_POST['id_grupo'] ?? 0;
$titulo   = trim($_POST['titulo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$docente_cedula = isset($_SESSION['cedula']) ? intval($_SESSION['cedula']) : 0;

if($id_grupo && $titulo && $mensaje && $docente_cedula){

    $fecha = date("Y-m-d H:i:s");
    $visto_estudiante = 0;
    $visto_adscripto  = 0;

    // INSERT en notificaciones
    $sql = "INSERT INTO notificaciones 
            (id_grupo, docente_cedula, titulo, mensaje, fecha, visto_estudiante, visto_adscripto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if(!$stmt) die("Error prepare notificaciones: ".$con->error);

    if(!$stmt->bind_param("isssiii", $id_grupo, $docente_cedula, $titulo, $mensaje, $fecha, $visto_estudiante, $visto_adscripto))
        die("Error bind_param: ".$stmt->error);

    if(!$stmt->execute()) die("Error execute: ".$stmt->error);

    $id_notificacion = $stmt->insert_id;
    $stmt->close();

    // INSERT en Recibe
    $sqlEstudiantes = "SELECT cedula FROM usuario WHERE id_grupo = ?";
    $stmtEst = $con->prepare($sqlEstudiantes);
    if(!$stmtEst) die("Error prepare usuarios: ".$con->error);

    $stmtEst->bind_param("i", $id_grupo);
    $stmtEst->execute();
    $resEst = $stmtEst->get_result();

    while($row = $resEst->fetch_assoc()){
        $cedula_estudiante = $row['cedula'];
        $sqlRecibe = "INSERT INTO Recibe (cedula_usuario, id_notificacion, visto) VALUES (?, ?, 0)";
        $stmtRecibe = $con->prepare($sqlRecibe);
        if(!$stmtRecibe) die("Error prepare Recibe: ".$con->error);
        $stmtRecibe->bind_param("ii", $cedula_estudiante, $id_notificacion);
        if(!$stmtRecibe->execute()) die("Error execute Recibe: ".$stmtRecibe->error);
        $stmtRecibe->close();
    }

    $stmtEst->close();
    $_SESSION['msg_notificacion'] = "Notificación enviada correctamente";

} else {
    $_SESSION['error_notificacion'] = "Faltan datos obligatorios";
}

header("Location: indexadministrativo.php");
exit();
?>



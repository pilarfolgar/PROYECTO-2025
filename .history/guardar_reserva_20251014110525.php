<?php
require("conexion.php");
$con = conectar_bd();
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $con->real_escape_string($_POST['nombre']);
    $cedula = $con->real_escape_string($_POST['cedula']);
    $id_aula = intval($_POST['id_aula']);
    $aula_nombre = $con->real_escape_string($_POST['aula_nombre']);
    $fecha = $con->real_escape_string($_POST['fecha']);
    $hora_inicio = $con->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $con->real_escape_string($_POST['hora_fin']);
    $id_grupo = intval($_POST['id_grupo']);

    // Verificar disponibilidad
    $sql_check = "SELECT * FROM reservas
                  WHERE id_aula=$id_aula AND fecha='$fecha'
                  AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
                       OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
                       OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";

    $result = $con->query($sql_check);
    if($result->num_rows > 0){
        echo json_encode(['success'=>false, 'message'=>'El aula ya está reservada en ese horario.']);
        exit;
    }

    // Insertar reserva
    $sql = "INSERT INTO reservas 
            (id_aula, cedula, nombre, aula, fecha, hora_inicio, hora_fin, id_grupo) 
            VALUES 
            ($id_aula, '$cedula', '$nombre', '$aula_nombre', '$fecha', '$hora_inicio', '$hora_fin', $id_grupo)";

    if($con->query($sql)){
        echo json_encode(['success'=>true, 'message'=>'Reserva confirmada ✅']);
    } else {
        echo json_encode(['success'=>false, 'message'=>'Error al guardar la reserva: '.$con->error]);
    }
}
?>

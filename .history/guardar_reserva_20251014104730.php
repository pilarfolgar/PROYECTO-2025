<?php
require("conexion.php");
$con = conectar_bd();
header('Content-Type: application/json');
echo json_encode(['success'=>true]);
 if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nombre = $con->real_escape_string($_POST['nombre']);
    $aula_nombre = $con->real_escape_string($_POST['aula_nombre']);
    $fecha = $con->real_escape_string($_POST['fecha']);
    $hora_inicio = $con->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $con->real_escape_string($_POST['hora_fin']);
    $id_grupo = intval($_POST['id_grupo']);

    // Validar disponibilidad
    $sql_check = "SELECT * FROM reservas
                  WHERE aula='$aula_nombre' AND fecha='$fecha'
                  AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
                       OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
                       OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";

    $res = $con->query($sql_check);
    if($res->num_rows > 0){
        echo json_encode(['success'=>false,'message'=>'El aula ya está reservada en ese horario.']);
        exit;
    }

    // Insertar reserva
    $sql_insert = "INSERT INTO reservas (nombre,aula,fecha,hora_inicio,hora_fin,grupo)
                   VALUES ('$nombre','$aula_nombre','$fecha','$hora_inicio','$hora_fin','$id_grupo')";

    if($con->query($sql_insert)){
        echo json_encode(['success'=>true,'message'=>'Reserva confirmada ✅']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Error al guardar reserva: '.$con->error]);
    }
}
?>

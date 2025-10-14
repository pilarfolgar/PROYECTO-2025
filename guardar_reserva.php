<?php
require("conexion.php");
$con = conectar_bd();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = $con->real_escape_string($_POST['nombre']);
    $aula = $con->real_escape_string($_POST['aula']);
    $fecha = $con->real_escape_string($_POST['fecha']);
    $hora_inicio = $con->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $con->real_escape_string($_POST['hora_fin']);
    $grupo = $con->real_escape_string($_POST['grupo']);

    // Verificar disponibilidad
    $sql_check = "SELECT * FROM reserva
                  WHERE aula='$aula' AND fecha='$fecha'
                  AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
                  OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
                  OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";

    $result = $con->query($sql_check);
    if($result->num_rows > 0){
        echo json_encode(['success'=>false, 'message'=>'El aula ya está reservada en ese horario.']);
        exit;
    }

    $sql = "INSERT INTO reservas (nombre,aula,fecha,hora_inicio,hora_fin,grupo)
            VALUES ('$nombre','$aula','$fecha','$hora_inicio','$hora_fin','$grupo')";
    
    if($con->query($sql)){
        echo json_encode(['success'=>true, 'message'=>'Reserva confirmada ✅']);
    } else {
        echo json_encode(['success'=>false, 'message'=>'Error al guardar la reserva.']);
    }
}
?>

<?php
require("conexion.php");
$con = conectar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $con->real_escape_string($_POST['nombre']);
    $aula = $con->real_escape_string($_POST['aula']);
    $fecha = $con->real_escape_string($_POST['fecha']);
    $hora_inicio = $con->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $con->real_escape_string($_POST['hora_fin']);
    $grupo = $con->real_escape_string($_POST['grupo']);

    // Verificar si la aula ya está reservada en ese horario
    $sql_check = "SELECT * FROM reservas 
                  WHERE aula='$aula' AND fecha='$fecha' 
                  AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio') 
                       OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin') 
                       OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";
    $result = $con->query($sql_check);

    if ($result->num_rows > 0) {
        echo "Error: El aula ya está reservada en ese horario.";
        exit;
    }

    // Insertar reserva
    $sql = "INSERT INTO reservas (nombre, aula, fecha, hora_inicio, hora_fin, grupo) 
            VALUES ('$nombre', '$aula', '$fecha', '$hora_inicio', '$hora_fin', '$grupo')";
    
    if ($con->query($sql)) {
        echo "Reserva confirmada ✅";
    } else {
        echo "Error al guardar la reserva.";
    }
}
?>

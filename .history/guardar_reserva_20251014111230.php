<?php
// Siempre JSON
header('Content-Type: application/json');

// Mostrar errores para depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("conexion.php");

$con = conectar_bd();
if (!$con) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos']);
    exit;
}

// Solo procesar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Recibir y validar datos
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$id_aula = isset($_POST['id_aula']) ? intval($_POST['id_aula']) : 0;
$aula_nombre = isset($_POST['aula_nombre']) ? trim($_POST['aula_nombre']) : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$hora_inicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : '';
$hora_fin = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : '';
$id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : 0;

// Validar campos obligatorios
if (!$nombre || !$id_aula || !$aula_nombre || !$fecha || !$hora_inicio || !$hora_fin || !$id_grupo) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Verificar disponibilidad
$sql_check = "SELECT * FROM reservas
              WHERE id_aula = $id_aula AND fecha = '$fecha'
              AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
              OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
              OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";

$result = $con->query($sql_check);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Error al verificar disponibilidad: ' . $con->error]);
    exit;
}

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El aula ya está reservada en ese horario.']);
    exit;
}

// Insertar reserva
$sql_insert = "INSERT INTO reservas (id_aula, nombre, aula, fecha, hora_inicio, hora_fin, grupo)
               VALUES ($id_aula, '".$con->real_escape_string($nombre)."', '".$con->real_escape_string($aula_nombre)."',
                       '$fecha', '$hora_inicio', '$hora_fin', $id_grupo)";

if ($con->query($sql_insert)) {
    echo json_encode(['success' => true, 'message' => 'Reserva confirmada ✅']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la reserva: ' . $con->error]);
}

?>

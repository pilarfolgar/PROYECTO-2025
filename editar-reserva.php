<?php
session_start();
require("conexion.php");
$con = conectar_bd();
require("header.php"); 

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Traer datos actuales de la reserva
$sql = "SELECT * FROM reserva WHERE id_reserva = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

if (!$reserva) {
    $_SESSION['error_reserva'] = "Reserva no encontrada.";
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_aula = $_POST['id_aula'];
    $id_grupo = $_POST['id_grupo'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    $sql = "UPDATE reserva SET nombre=?, id_aula=?, grupo=?, fecha=?, hora_inicio=?, hora_fin=? WHERE id_reserva=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siisssi", $nombre, $id_aula, $id_grupo, $fecha, $hora_inicio, $hora_fin, $id);

    if ($stmt->execute()) {
        $_SESSION['msg_reserva'] = "Reserva actualizada con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit();
    } else {
        $_SESSION['error_reserva'] = "Error al actualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Reserva</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
<h2>✏️ Editar Reserva</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $reserva['id_reserva'] ?>">

    <div class="mb-3">
        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($reserva['nombre']) ?>" required>
    </div>
    <div class="mb-3">
        <label>ID Aula:</label>
        <input type="number" name="id_aula" class="form-control" value="<?= $reserva['id_aula'] ?>" required>
    </div>
    <div class="mb-3">
        <label>ID Grupo:</label>
        <input type="number" name="id_grupo" class="form-control" value="<?= $reserva['grupo'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Fecha:</label>
        <input type="date" name="fecha" class="form-control" value="<?= $reserva['fecha'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Hora Inicio:</label>
        <input type="time" name="hora_inicio" class="form-control" value="<?= $reserva['hora_inicio'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Hora Fin:</label>
        <input type="time" name="hora_fin" class="form-control" value="<?= $reserva['hora_fin'] ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
</form>
</div>
</body>
</html>

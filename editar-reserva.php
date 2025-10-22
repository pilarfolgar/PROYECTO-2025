<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Traer datos actuales de la reserva
$sql = "SELECT * FROM reservas WHERE id_reserva = ?";
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

// Traer listas de aulas y grupos
$aulas = $con->query("SELECT * FROM aula ORDER BY nombre");
$grupos = $con->query("SELECT * FROM grupo ORDER BY nombre");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_aula = intval($_POST['id_aula']);
    $grupo = intval($_POST['grupo']);
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    // Obtener nombre del aula
    $sql_aula = "SELECT nombre FROM aula WHERE id_aula = ?";
    $stmt_aula = $con->prepare($sql_aula);
    $stmt_aula->bind_param("i", $id_aula);
    $stmt_aula->execute();
    $res_aula = $stmt_aula->get_result()->fetch_assoc();
    $aula_nombre = $res_aula['nombre'] ?? '';

    // Verificar disponibilidad
    $sql_check = "SELECT * FROM reservas
                  WHERE id_aula = ? AND fecha = ? AND id_reserva != ? 
                  AND ((hora_inicio <= ? AND hora_fin > ?) OR (hora_inicio < ? AND hora_fin >= ?) OR (hora_inicio >= ? AND hora_fin <= ?))";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("isissssss", $id_aula, $fecha, $id, $hora_inicio, $hora_inicio, $hora_fin, $hora_fin, $hora_inicio, $hora_fin);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    if ($check_result->num_rows > 0) {
        $_SESSION['error_reserva'] = "El aula ya está reservada en ese horario.";
        header("Location: editar-reserva.php?id=$id");
        exit();
    }

    // Actualizar reserva
    $sql_update = "UPDATE reservas SET nombre=?, id_aula=?, aula=?, grupo=?, fecha=?, hora_inicio=?, hora_fin=? WHERE id_reserva=?";
    $stmt_update = $con->prepare($sql_update);
    $stmt_update->bind_param("sisi ss i", $nombre, $id_aula, $aula_nombre, $grupo, $fecha, $hora_inicio, $hora_fin, $id);

    if ($stmt_update->execute()) {
        $_SESSION['msg_reserva'] = "Reserva actualizada con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit();
    } else {
        $_SESSION['error_reserva'] = "Error al actualizar: " . $stmt_update->error;
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
        <label>Nombre de la reserva:</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($reserva['nombre']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Aula:</label>
        <select name="id_aula" class="form-select" required>
            <option value="">Seleccionar aula</option>
            <?php while($aula = $aulas->fetch_assoc()): ?>
                <option value="<?= $aula['id_aula'] ?>" <?= $aula['id_aula'] == $reserva['id_aula'] ? 'selected' : '' ?>><?= htmlspecialchars($aula['nombre']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Grupo:</label>
        <select name="grupo" class="form-select" required>
            <option value="">Seleccionar grupo</option>
            <?php while($g = $grupos->fetch_assoc()): ?>
                <option value="<?= $g['id_grupo'] ?>" <?= $g['id_grupo'] == $reserva['grupo'] ? 'selected' : '' ?>><?= htmlspecialchars($g['nombre']) ?></option>
            <?php endwhile; ?>
        </select>
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

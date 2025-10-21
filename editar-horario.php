<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Traer datos actuales del horario
$sql = "SELECT * FROM horarios WHERE id_horario = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$horario = $result->fetch_assoc();

if (!$horario) {
    $_SESSION['error_horario'] = "Horario no encontrado.";
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_asignatura = $_POST['id_asignatura'];
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $aula = $_POST['aula'];
    $clase = $_POST['clase'];

    $sql = "UPDATE horarios SET id_asignatura=?, dia=?, hora_inicio=?, hora_fin=?, aula=?, clase=? WHERE id_horario=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssssi", $id_asignatura, $dia, $hora_inicio, $hora_fin, $aula, $clase, $id);

    if ($stmt->execute()) {
        $_SESSION['msg_horario'] = "Horario actualizado con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit();
    } else {
        $_SESSION['error_horario'] = "Error al actualizar: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Horario</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="estilos.css">

</head>
<body>
<div class="container mt-4">
<h2>✏️ Editar Horario</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $horario['id_horario'] ?>">

    <div class="mb-3">
        <label>ID Asignatura:</label>
        <input type="number" name="id_asignatura" class="form-control" value="<?= $horario['id_asignatura'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Día:</label>
        <input type="text" name="dia" class="form-control" value="<?= $horario['dia'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Hora Inicio:</label>
        <input type="time" name="hora_inicio" class="form-control" value="<?= $horario['hora_inicio'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Hora Fin:</label>
        <input type="time" name="hora_fin" class="form-control" value="<?= $horario['hora_fin'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Aula:</label>
        <input type="text" name="aula" class="form-control" value="<?= $horario['aula'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Clase:</label>
        <input type="text" name="clase" class="form-control" value="<?= $horario['clase'] ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
</form>
</div>
</body>
</html>

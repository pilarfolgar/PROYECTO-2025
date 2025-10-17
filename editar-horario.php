<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM horarios WHERE id_horario = $id";
    $result = $con->query($sql);
    $horario = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $id_asignatura = $_POST['id_asignatura'];
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $aula = $_POST['aula'];
    $clase = $_POST['clase'];

    $sql = "UPDATE horarios 
            SET id_asignatura='$id_asignatura', dia='$dia', hora_inicio='$hora_inicio', hora_fin='$hora_fin', aula='$aula', clase='$clase' 
            WHERE id_horario=$id";
    if ($con->query($sql)) {
        header("Location: indexadministrativoDatos.php?msg=Horario actualizado correctamente");
    } else {
        echo "Error al actualizar: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Horario</title>
</head>
<body>
<h2>Editar Horario</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($horario['id_horario']) ?>">

    <label>ID Asignatura:</label>
    <input type="text" name="id_asignatura" value="<?= htmlspecialchars($horario['id_asignatura']) ?>" required><br>

    <label>Día:</label>
    <input type="text" name="dia" value="<?= htmlspecialchars($horario['dia']) ?>" required><br>

    <label>Hora Inicio:</label>
    <input type="time" name="hora_inicio" value="<?= htmlspecialchars($horario['hora_inicio']) ?>" required><br>

    <label>Hora Fin:</label>
    <input type="time" name="hora_fin" value="<?= htmlspecialchars($horario['hora_fin']) ?>" required><br>

    <label>Aula:</label>
    <input type="text" name="aula" value="<?= htmlspecialchars($horario['aula']) ?>" required><br>

    <label>Clase:</label>
    <input type="text" name="clase" value="<?= htmlspecialchars($horario['clase']) ?>" required><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>

<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM asignaturas WHERE id_asignatura = $id";
    $result = $con->query($sql);
    $asignatura = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $profesor = $_POST['profesor'];

    $sql = "UPDATE asignaturas SET nombre='$nombre', profesor='$profesor' WHERE id_asignatura=$id";
    if ($con->query($sql)) {
        header("Location: indexadministrativo_datos.php?msg=Asignatura actualizada correctamente");
    } else {
        echo "Error al actualizar: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asignatura</title>
</head>
<body>
<h2>Editar Asignatura</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($asignatura['id_asignatura']) ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($asignatura['nombre']) ?>" required><br>

    <label>Profesor:</label>
    <input type="text" name="profesor" value="<?= htmlspecialchars($asignatura['profesor']) ?>" required><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>

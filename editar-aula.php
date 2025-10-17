<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Traer datos actuales
    $sql = "SELECT * FROM aula WHERE codigo = '$codigo'";
    $result = $con->query($sql);
    $aula = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $capacidad = $_POST['capacidad'];
    $ubicacion = $_POST['ubicacion'];
    $tipo = $_POST['tipo'];

    $sql = "UPDATE aula SET capacidad='$capacidad', ubicacion='$ubicacion', tipo='$tipo' WHERE codigo='$codigo'";
    if ($con->query($sql)) {
        header("Location: indexadministrativo_datos.php?msg=Aula actualizada correctamente");
    } else {
        echo "Error al actualizar: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Aula</title>
</head>
<body>
<h2>Editar Aula</h2>
<form method="POST">
    <input type="hidden" name="codigo" value="<?= htmlspecialchars($aula['codigo']) ?>">
    <label>Capacidad:</label>
    <input type="text" name="capacidad" value="<?= htmlspecialchars($aula['capacidad']) ?>" required><br>

    <label>Ubicación:</label>
    <input type="text" name="ubicacion" value="<?= htmlspecialchars($aula['ubicacion']) ?>" required><br>

    <label>Tipo:</label>
    <input type="text" name="tipo" value="<?= htmlspecialchars($aula['tipo']) ?>" required><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>

<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

$sql = "SELECT * FROM asignatura WHERE id_asignatura = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$asignatura = $result->fetch_assoc();

if (!$asignatura) {
    $_SESSION['error_asignatura'] = "Asignatura no encontrada.";
    header("Location: indexadministrativoDatos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];

    $sql = "UPDATE asignatura SET nombre=?, codigo=? WHERE id_asignatura=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $codigo, $id);

    if ($stmt->execute()) {
        $_SESSION['msg_asignatura'] = "Asignatura modificada con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit();
    } else {
        $_SESSION['error_asignatura'] = "Error al actualizar: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Asignatura</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container mt-4">
<h2>✏️ Editar Asignatura</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $asignatura['id_asignatura'] ?>">
    
    <div class="mb-3">
        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($asignatura['nombre']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Código:</label>
        <input type="text" name="codigo" class="form-control" value="<?= htmlspecialchars($asignatura['codigo']) ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
</form>
</div>
</body>
</html>

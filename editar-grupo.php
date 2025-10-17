<?php
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if(!$id) {
    die("ID no proporcionado");
}

// Consultar datos del grupo
$sql = "SELECT * FROM grupo WHERE id_grupo = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$grupo = $result->fetch_assoc();

if(!$grupo) die("Grupo no encontrado");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $orientacion = $_POST['orientacion'];
    $cantidad = $_POST['cantidad'];

    $sql = "UPDATE grupo SET nombre=?, orientacion=?, cantidad=? WHERE id_grupo=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssii", $nombre, $orientacion, $cantidad, $id);
    $stmt->execute();

    header("Location: indexadministrativoDatos.php");
    exit;
}
?>

<form method="POST">
    <input type="text" name="nombre" value="<?= htmlspecialchars($grupo['nombre']) ?>" required>
    <input type="text" name="orientacion" value="<?= htmlspecialchars($grupo['orientacion']) ?>" required>
    <input type="number" name="cantidad" value="<?= $grupo['cantidad'] ?>" required>
    <button type="submit">Guardar cambios</button>
</form>

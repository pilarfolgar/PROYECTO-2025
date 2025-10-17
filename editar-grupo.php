<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no proporcionado");
}

// Consultar datos del grupo
$sql = "SELECT * FROM grupo WHERE id_grupo = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$grupo = $result->fetch_assoc();

if (!$grupo) die("Grupo no encontrado");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $orientacion = $_POST['orientacion'];
    $cantidad_estudiantes = (int)$_POST['cantidad_estudiantes'];

    $sql = "UPDATE grupo SET nombre=?, orientacion=?, cantidad_estudiantes=? WHERE id_grupo=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssii", $nombre, $orientacion, $cantidad_estudiantes, $id);
    if ($stmt->execute()) {
        $_SESSION['msg_grupo'] = "Grupo actualizado con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit;
    } else {
        $_SESSION['error_grupo'] = "Error al actualizar el grupo: " . $stmt->error;
        header("Location: indexadministrativoDatos.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Grupo</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>
<main class="container mt-4">
    <h2>Editar Grupo</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($grupo['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Orientación</label>
            <select class="form-select" name="orientacion" required>
                <option value="Tec. de la Información" <?= $grupo['orientacion'] == "Tec. de la Información" ? "selected" : "" ?>>Tecnologías de la información</option>
                <option value="Tec. de la Información Bilingüe" <?= $grupo['orientacion'] == "Tec. de la Información Bilingüe" ? "selected" : "" ?>>Tecnologías de la información Bilingüe</option>
                <option value="Tecnología" <?= $grupo['orientacion'] == "Tecnología" ? "selected" : "" ?>>Tecnólogo en Ciberseguridad</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Cantidad de estudiantes</label>
            <input type="number" name="cantidad_estudiantes" class="form-control" value="<?= $grupo['cantidad_estudiantes'] ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>
<?php require("footer.php"); ?>
</body>
</html>

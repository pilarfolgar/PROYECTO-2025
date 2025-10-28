<?php
session_start();
require("conexion.php");
$con = conectar_bd();
require("header.php");
?>

<?php
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Traer datos actuales de forma segura
    $sql = "SELECT * FROM aula WHERE codigo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $aula = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $capacidad = $_POST['capacidad'];
    $ubicacion = $_POST['ubicacion'];
    $tipo = $_POST['tipo'];

    // Actualizar con prepared statement
    $sql = "UPDATE aula SET capacidad = ?, ubicacion = ?, tipo = ? WHERE codigo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $capacidad, $ubicacion, $tipo, $codigo);

    if ($stmt->execute()) {
        $_SESSION['msg_aula'] = "Aula modificada con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit();
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Aula</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>✏️ Editar Aula</h2>
    <form method="POST">
        <input type="hidden" name="codigo" value="<?= htmlspecialchars($aula['codigo']) ?>">

        <div class="mb-3">
            <label>Código:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($aula['codigo']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label>Capacidad:</label>
            <input type="text" name="capacidad" class="form-control" value="<?= htmlspecialchars($aula['capacidad']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Ubicación:</label>
            <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($aula['ubicacion']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Tipo:</label>
            <input type="text" name="tipo" class="form-control" value="<?= htmlspecialchars($aula['tipo']) ?>" required>
        </div>

        <?php if (!empty($aula['imagen'])): ?>
        <div class="mb-3">
            <label>Imagen actual:</label><br>
            <img src="<?= htmlspecialchars($aula['imagen']) ?>" alt="Aula" style="width:150px; height:auto;">
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require("footer.php"); ?>
</body>
</html>

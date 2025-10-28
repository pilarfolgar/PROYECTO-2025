<?php
session_start();
require("conexion.php");
$con = conectar_bd();
require("header.php");

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

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

    // Procesar imagen si se subió una nueva
    $imagenPath = $aula['imagen']; // Mantener la actual si no se sube otra
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = "uploads/" . $nombreArchivo; // Carpeta donde guardas las imágenes
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagenPath = $rutaDestino;
        }
    }

    $sql = "UPDATE aula SET capacidad = ?, ubicacion = ?, tipo = ?, imagen = ? WHERE codigo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $capacidad, $ubicacion, $tipo, $imagenPath, $codigo);

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
    <form method="POST" enctype="multipart/form-data">
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

        <div class="mb-3">
            <label>Imagen actual:</label><br>
            <?php if (!empty($aula['imagen'])): ?>
                <img src="<?= htmlspecialchars($aula['imagen']) ?>" alt="Aula" style="width:150px; height:auto;">
            <?php else: ?>
                <p>No hay imagen.</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Cambiar imagen:</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require("footer.php"); ?>
</body>
</html>

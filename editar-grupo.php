<?php
session_start();
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

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $orientacion = $_POST['orientacion'];
    $cantidad = $_POST['cantidad'];

    $sql = "UPDATE grupo SET nombre=?, orientacion=?, cantidad=? WHERE id_grupo=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssii", $nombre, $orientacion, $cantidad, $id);
    $stmt->execute();

    $_SESSION['msg_grupo'] = "Grupo actualizado con éxito";
    header("Location: indexadministrativoDatos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Grupo</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>

<main class="contenedor">
  <h1 class="mb-4">✏️ Editar Grupo</h1>

  <form method="POST" class="needs-validation form-reserva-style" novalidate>
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre del grupo</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($grupo['nombre']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="orientacion" class="form-label">Orientación</label>
      <select class="form-select" id="orientacion" name="orientacion" required>
        <option value="Tec. de la Información" <?= $grupo['orientacion'] == "Tec. de la Información" ? "selected" : "" ?>>Tecnologías de la información</option>
        <option value="Tec. de la Información Bilingüe" <?= $grupo['orientacion'] == "Tec. de la Información Bilingüe" ? "selected" : "" ?>>Tecnologías de la información Bilingüe</option>
        <option value="Tecnología" <?= $grupo['orientacion'] == "Tecnología" ? "selected" : "" ?>>Tecnólogo en Ciberseguridad</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="cantidad" class="form-label">Cantidad de estudiantes</label>
      <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?= $grupo['cantidad'] ?>" min="1" required>
    </div>

    <button type="submit" class="boton mt-3">Guardar cambios</button>
    <a href="indexadministrativoDatos.php" class="btn btn-secondary mt-3">Cancelar</a>
  </form>
</main>

<?php require("footer.php"); ?>
</body>
</html>

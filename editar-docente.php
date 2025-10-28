<?php
session_start();
require("conexion.php");
$con = conectar_bd();


$cedula = $_GET['cedula'] ?? null;
if (!$cedula) {
    die("Cédula no proporcionada");
}

// Traer datos actuales
$sql = "SELECT * FROM usuario WHERE cedula=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $cedula);
$stmt->execute();
$result = $stmt->get_result();
$docente = $result->fetch_assoc();

if (!$docente) die("Docente no encontrado");

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuario SET nombrecompleto=?, apellido=?, email=?, telefono=? WHERE cedula=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $cedula);

    if ($stmt->execute()) {
        $_SESSION['msg_docente'] = "Docente modificado con éxito ✅";
        header("Location: indexadministrativoDatos.php");
        exit;
    } else {
        $_SESSION['error_docente'] = "Error al actualizar el docente: " . $stmt->error;
        header("Location: indexadministrativoDatos.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Docente</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="estilos.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>
<main class="container mt-4">
    <h2>✏️ Editar Docente</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($docente['nombrecompleto']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($docente['apellido']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($docente['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="tel" name="telefono" class="form-control" value="<?= htmlspecialchars($docente['telefono']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>
<?php require("footer.php"); ?>
</body>
</html>

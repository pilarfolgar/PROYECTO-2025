<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula = $_GET['cedula'] ?? null;

if(!$cedula){
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Guardar cambios
if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuario SET nombrecompleto=?, apellido=?, email=?, telefono=? WHERE cedula=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi",$nombre,$apellido,$email,$telefono,$cedula);
    $stmt->execute();
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Traer datos actuales
$sql = "SELECT * FROM usuario WHERE cedula=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i",$cedula);
$stmt->execute();
$result = $stmt->get_result();
$docente = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Docente</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Docente</h2>
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
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($docente['telefono']) ?>">
        </div>
        <button type="submit" name="guardar" class="btn btn-success">Guardar Cambios</button>
        <a href="indexadministrativoDatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>

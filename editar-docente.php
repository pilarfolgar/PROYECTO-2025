<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula = $_GET['cedula'] ?? null;
if (!$cedula) {
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Traer datos actuales
$sql = "SELECT * FROM usuario WHERE cedula=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $cedula);
$stmt->execute();
$result = $stmt->get_result();
$docente = $result->fetch_assoc();

if (!$docente) {
    $_SESSION['error_docente'] = "Docente no encontrado.";
    header("Location: indexadministrativoDatos.php");
    exit();
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuario SET nombrecompleto=?, apellido=?, email=?, telefono=? WHERE cedula=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $cedula);
    $stmt->execute();

    $_SESSION['msg_docente'] = "Docente modificado con éxito ✅";
    header("Location: indexadministrativoDatos.php");
    exit();
}
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
<h2>✏️ Editar Docente</h2>
<form method="POST">
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($docente['nombrecompleto']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Apellido</label>
        <input type="text" name

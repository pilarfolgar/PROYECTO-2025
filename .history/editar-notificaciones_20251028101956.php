<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Solo usuarios administrativos
if (!isset($_SESSION['cedula']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: iniciosesion.php");
    exit();
}

$id = $_GET['id'] ?? null;

// Verificar que la notificación exista
$sql = "SELECT * FROM notificaciones WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('No existe la notificación.');window.location='indexadministrativo.php';</script>";
    exit();
}

$noti = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);

    $update = $con->prepare("
        UPDATE notificaciones 
        SET titulo = ?, mensaje = ?
        WHERE id = ?
    ");
    $update->bind_param("ssi", $titulo, $mensaje, $id);

    if ($update->execute()) {
        echo "<script>alert('✅ Notificación modificada correctamente.');window.location='indexadministrativo.php';</script>";
    } else {
        echo "<script>alert('❌ Error al actualizar la notificación.');window.location='indexadministrativo.php';</script>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Notificación</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
      <h4>✏️ Modificar Notificación</h4>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($noti['titulo']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mensaje</label>
          <textarea name="mensaje" class="form-control" rows="5" required><?= htmlspecialchars($noti['mensaje']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
        <a href="indexadministrativo.php" class="btn btn-secondary">↩️ Volver</a>
      </form>
    </div>
  </div>
</div>
</body

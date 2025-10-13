<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$nombre_estudiante = $_SESSION['usuario_nombre'] ?? 'Estudiante';

// Traer avisos generales (para todos)
$sql = "SELECT id, titulo, mensaje, fecha FROM avisos ORDER BY fecha DESC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Avisos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php require("header.php"); ?>

<section class="mis-cursos my-5">
  <h2 class="text-center mb-4">Avisos Generales</h2>
  <div class="docentes-grid">
    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="estudiante-card">
          <div class="docente-photo bg-secondary text-white fs-1 d-flex justify-content-center align-items-center">
            <i class="bi bi-clipboard"></i>
          </div>
          <div class="docente-name"><?php echo htmlspecialchars($row['titulo']); ?></div>
          <div class="docente-subject"><?php echo nl2br(htmlspecialchars($row['mensaje'])); ?></div>
          <small class="text-muted">Fecha: <?php echo date("d/m/Y", strtotime($row['fecha'])); ?></small>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No hay avisos disponibles.</p>
    <?php endif; ?>
  </div>
</section>

<footer class="footer">
  &copy; <?php echo date("Y"); ?> Instituto Tecnológico Superior de Paysandú
</footer>
</body>
</html>

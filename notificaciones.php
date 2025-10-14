<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// 1️⃣ Obtener la cédula del usuario logueado
$usuario_cedula = $_SESSION['cedula'] ?? '';
$nombre_estudiante = $_SESSION['nombrecompleto'] ?? 'Estudiante';

// 2️⃣ Buscar el grupo del estudiante
$sqlGrupo = "SELECT id_grupo FROM usuario WHERE cedula = ?";
$stmtGrupo = $con->prepare($sqlGrupo);
$stmtGrupo->bind_param("i", $usuario_cedula);
$stmtGrupo->execute();
$resGrupo = $stmtGrupo->get_result();
$grupo_id = 0;
if ($fila = $resGrupo->fetch_assoc()) {
    $grupo_id = $fila['id_grupo'];
}

// 3️⃣ Traer todas las notificaciones de ese grupo
$sql = "SELECT id, docente_cedula , titulo, mensaje, fecha, visto_estudiante 
        FROM notificaciones 
        WHERE id_grupo = ? 
        ORDER BY fecha DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notificaciones</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php require("header.php"); ?>

<section class="mis-cursos my-5">
  <h2 class="text-center mb-4">
    📢 Notificaciones de tu grupo – <?php echo htmlspecialchars($nombre_estudiante); ?>
  </h2>
  <div class="docentes-grid">
    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="estudiante-card <?php echo ($row['visto_estudiante'] ? 'leido' : 'no-leido'); ?>">
          <div class="docente-photo bg-info text-white fs-1 d-flex justify-content-center align-items-center">
            <i class="bi bi-bell"></i>
          </div>
          <div class="docente-name">
            <?php echo htmlspecialchars($row['titulo']); ?>
          </div>
          <div class="docente-subject">
            <?php echo nl2br(htmlspecialchars($row['mensaje'])); ?>
          </div>
          <small class="text-muted">
            Fecha: <?php echo date("d/m/Y H:i", strtotime($row['fecha'])); ?>
          </small>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">📭 No tienes notificaciones nuevas para tu grupo.</p>
    <?php endif; ?>
  </div>
</section>

<footer class="footer">
  &copy; <?php echo date("Y"); ?> Instituto Tecnológico Superior de Paysandú
</footer>
</body>
</html>


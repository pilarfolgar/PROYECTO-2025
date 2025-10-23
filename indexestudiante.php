<?php
require("seguridad.php");
require("conexion.php");

$con = conectar_bd();

// Obtener id_grupo del estudiante
$cedula = $_SESSION['cedula'];
$stmt = $con->prepare("SELECT id_grupo, nombrecompleto FROM usuario WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $id_grupo = $row['id_grupo'];
    $nombre_estudiante = $row['nombrecompleto'];
} else {
    $id_grupo = null;
    $nombre_estudiante = "Estudiante";
}
$stmt->close();

// Traer horarios solo si tiene grupo asignado
$horarios = [];
if ($id_grupo) {
    $sql = "SELECT h.dia, h.hora_inicio, h.hora_fin, h.clase, h.aula, a.nombre AS asignatura
            FROM horarios h
            INNER JOIN asignatura a ON h.id_asignatura = a.id_asignatura
            WHERE h.id_grupo = ?
            ORDER BY FIELD(h.dia,'lunes','martes','miercoles','jueves','viernes'), h.hora_inicio ASC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();
    $horarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Obtener nombre del grupo
$nombre_grupo = "Sin asignar";
if ($id_grupo) {
    $stmt = $con->prepare("SELECT nombre FROM grupo WHERE id_grupo = ?");
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $nombre_grupo = $row['nombre'];
    }
    $stmt->close();
}

// Organizar horarios por día
$dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
$horarios_por_dia = [];
foreach ($dias as $dia) {
    $horarios_por_dia[$dia] = [];
}
foreach ($horarios as $h) {
    $horarios_por_dia[strtolower($h['dia'])][] = $h;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Estudiante</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styleestudiante.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="styleestudiante.css">

</head>
<body>
<?php require("header.php"); ?>

<section class="mis-cursos my-5">
  <h2 class="text-center mb-4">Bienvenido, <?= htmlspecialchars($nombre_estudiante) ?></h2>
  <div class="docentes-grid">

    <!-- Tarjeta Notificaciones -->
    <div class="estudiante-card">
      <div class="docente-photo bg-primary text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-bell"></i>
      </div>
      <div class="docente-name">Notificaciones</div>
      <div class="docente-subject">Ver tus avisos importantes</div>
      <div class="notificaciones-estudiante mt-2">
      <ul class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
          <li class="list-group-item <?php echo $row['visto_estudiante'] ? '' : 'fw-bold'; ?>">
              <strong><?php echo htmlspecialchars($row['titulo']); ?></strong>
              <p><?php echo htmlspecialchars($row['mensaje']); ?></p>
              <small>De: <?php echo htmlspecialchars($row['docente_nombre']); ?> | <?php echo $row['fecha']; ?></small>
          </li>
      <?php endwhile; ?>
      </ul>
  </div>
      <a href="notificaciones.php" class="boton w-100 text-center">Ir a Notificaciones</a>
    </div>
    <?php
$sql = "SELECT n.id, n.titulo, n.mensaje, n.fecha, n.visto_estudiante, u.nombrecompleto AS docente_nombre
        FROM notificaciones n
        JOIN usuario u ON n.docente_cedula = u.cedula
        WHERE n.id_grupo = (SELECT id_grupo FROM usuario WHERE cedula = ?)
        AND n.rol_emisor = 'docente'
        ORDER BY n.fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $cedula);

$stmt->execute();
$result = $stmt->get_result();
?>




    <!-- Tarjeta Horario -->
    <div class="estudiante-card">
      <div class="docente-photo bg-warning text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-calendar-week"></i>
      </div>
      <div class="docente-name">Horario del Grupo</div>
      <div class="docente-subject">Visualiza tus clases y aulas</div>
      <a href="#horarioGrupo" id="verHorarioBtn" class="boton w-100 text-center">Ver Horario</a>
    </div>

    <!-- Tarjeta Mi Clase -->
    <div class="estudiante-card">
      <div class="docente-photo bg-info text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-people"></i>
      </div>
      <div class="docente-name">Mi Clase</div>
      <div class="docente-subject"><?= htmlspecialchars($nombre_grupo) ?></div>
    </div>
  </div>
</section>

<section class="container my-5" id="horarioGrupo">
    <h2 class="text-center mb-4">Horario de tu Grupo</h2>
    <?php if(count($horarios) > 0): ?>
        <div class="accordion" id="accordionHorarios">
            <?php foreach($dias as $index => $dia): ?>
                <?php if(count($horarios_por_dia[$dia]) > 0): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                <?= ucfirst($dia) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionHorarios">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Clase</th>
                                                <th>Aula</th>
                                                <th>Asignatura</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($horarios_por_dia[$dia] as $h): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($h['hora_inicio']) ?></td>
                                                    <td><?= htmlspecialchars($h['hora_fin']) ?></td>
                                                    <td><?= htmlspecialchars($h['clase']) ?></td>
                                                    <td><?= htmlspecialchars($h['aula']) ?></td>
                                                    <td><?= htmlspecialchars($h['asignatura']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No hay horarios asignados para tu grupo aún.</p>
    <?php endif; ?>
</section>

<!-- Botón flotante Reporte -->
<button id="btnAbrirReporte" class="btn-flotante">📝</button>

<!-- Overlay y Formulario Reporte -->
<div id="overlayReporte" class="formulario-overlay"></div>
<section id="form-reporte" class="formulario">
  <button type="button" class="cerrar" id="btnCerrarReporte">✖</button>
  <form id="reporteForm" action="guardar-reporte-.php" method="POST" class="needs-validation form-reserva-style" novalidate>
    <h2 class="form-title">Reportar Objeto Dañado</h2>
    <div class="mb-3">
      <label for="nombreReporte" class="form-label">Nombre</label>
      <input type="text" class="form-control" id="nombreReporte" name="nombre" required pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$">
      <div class="invalid-feedback">Por favor, ingrese un nombre válido (solo letras).</div>
    </div>
    <div class="mb-3">
      <label for="emailReporte" class="form-label">Email</label>
      <input type="email" class="form-control" id="emailReporte" name="email" required>
      <div class="invalid-feedback">Ingrese un correo electrónico válido.</div>
    </div>
    <div class="mb-3">
      <label for="objetoReporte" class="form-label">Objeto o área</label>
      <input type="text" class="form-control" id="objetoReporte" name="objeto" required>
      <div class="invalid-feedback">Este campo es obligatorio.</div>
    </div>
    <div class="mb-3">
      <label for="descripcionReporte" class="form-label">Descripción del problema</label>
      <textarea class="form-control" id="descripcionReporte" name="descripcion" rows="3" minlength="10" required></textarea>
      <div class="invalid-feedback">La descripción debe tener al menos 10 caracteres.</div>
    </div>
    <div class="mb-3">
      <label for="fechaReporte" class="form-label">Fecha del reporte</label>
      <input type="date" class="form-control" id="fechaReporte" name="fecha" required>
      <div class="invalid-feedback">Seleccione una fecha válida (no futura).</div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Enviar Reporte</button>
    <div id="mensajeReporte" class="mt-3 text-center"></div>
  </form>
</section>

<?php require("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>

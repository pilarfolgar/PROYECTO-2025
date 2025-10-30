<?php
require("seguridad.php");
require("conexion.php");

$con = conectar_bd();

// Obtener id_grupo y nombre del estudiante
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
    $sql = "SELECT 
                h.dia, 
                h.hora_inicio, 
                h.hora_fin, 
                h.clase, 
                h.aula, 
                a.nombre AS asignatura, 
                CONCAT(u.nombrecompleto,' ',u.apellido) AS docente
            FROM horarios h
            INNER JOIN asignatura a ON h.id_asignatura = a.id_asignatura
            LEFT JOIN usuario u ON h.docente_cedula = u.cedula
            WHERE h.id_grupo = ?
            ORDER BY FIELD(h.dia,'lunes','martes','miercoles','jueves','viernes'), h.hora_inicio ASC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();
    $horarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Nombre del grupo
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
$dias = ['lunes','martes','miercoles','jueves','viernes'];
$horarios_por_dia = [];
foreach ($dias as $dia) {
    $horarios_por_dia[$dia] = [];
}
foreach ($horarios as $h) {
    $dia = strtolower($h['dia']);
    if (isset($horarios_por_dia[$dia])) {
        $horarios_por_dia[$dia][] = $h;
    }
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
</head>
<body>
<?php require("header.php"); ?>

<section class="mis-cursos my-5">
  <h2 class="text-center mb-4">Bienvenido, <?= htmlspecialchars($nombre_estudiante) ?></h2>
  <div class="docentes-grid">

    <!-- Tarjeta Horario -->
    <div class="estudiante-card">
      <div class="docente-photo bg-warning text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-calendar-week"></i>
      </div>
      <div class="docente-name">Horario del Grupo</div>
      <div class="docente-subject">Visualiza tus clases y aulas</div>
      <?php if ($id_grupo): ?>
        <a href="#horarioGrupo" id="verHorarioBtn" class="boton w-100 text-center">Ver Horario</a>
      <?php else: ?>
        <div class="text-center text-muted mt-2">No tienes un grupo asignado</div>
      <?php endif; ?>
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

<?php if ($id_grupo): ?>
<section class="container my-5" id="horarioGrupo">
    <h2 class="text-center mb-4">Horario de tu Grupo</h2>

    <div class="dias-semana d-flex justify-content-center flex-wrap gap-2 mb-3">
    <?php foreach($dias as $dia): ?>
        <button class="boton btn-dia" data-dia="<?= $dia ?>"><?= ucfirst($dia) ?></button>
    <?php endforeach; ?>
    </div>

    <!-- Contenedor de horarios -->
    <div id="horarios-contenedor">
        <?php foreach($dias as $dia): ?>
            <div class="horario-dia d-none" id="horario-<?= $dia ?>">
                <?php if(count($horarios_por_dia[$dia]) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Clase</th>
                                    <th>Aula</th>
                                    <th>Asignatura</th>
                                    <th>Docente</th>
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
                                        <td><?= htmlspecialchars($h['docente'] ?? '—') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">No hay clases asignadas para <?= ucfirst($dia) ?>.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php require("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>

<?php
session_start(); 
require("conexion.php");
$con = conectar_bd();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrativo - InfraLex</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="styleindexadministrativo.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="admin-script.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<main class="contenedor" id="gestion">
  <!-- Tarjetas de acciones -->
  <div class="tarjeta">
    <h3>Docentes</h3>
    <p>Registrar y actualizar datos de los docentes.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-docente')">➕ Agregar Docente</a>
  </div>

  <div class="tarjeta">
    <h3>Asignaturas</h3>
    <p>Crear, modificar y administrar asignaturas.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-asignatura')">➕ Agregar Asignatura</a>
  </div>

  <div class="tarjeta">
    <h3>Horarios</h3>
    <p>Organizar y actualizar los horarios de clases.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-horario')">➕ Agregar Horario</a>
  </div>

  <div class="tarjeta">
    <h3>Aulas</h3>
    <p>Administrar aulas disponibles y asignaciones.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-aula')">➕ Agregar Aula</a>
  </div>

  <div class="tarjeta">
    <h3>Grupos</h3>
    <p>Crear y administrar grupos de estudiantes.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-grupo')">➕ Agregar Grupo</a>
  </div>

  <div class="tarjeta">
    <h3>Enviar Notificación</h3>
    <p>Informar cambios, avisos o recordatorios a un grupo de estudiantes.</p>
    <a href="#" class="boton" onclick="mostrarForm('form-notificacion')">➕ Enviar Notificación</a>
  </div>

  <div class="tarjeta">
    <h3>Reportes Objetos Rotos</h3>
    <p>Ver los reportes.</p>
    <button class="boton" id="btnVerReportes" onclick="mostrarReportes()">➕ Ver Reportes</button>
  </div>

  <div class="tarjeta">
    <h3>Reservas de Aulas</h3>
    <p>Visualizar y administrar reservas de aulas.</p>
    <button class="boton" onclick="mostrarReservas()">➕ Ver Reservas</button>
  </div>
</main>

<div class="text-center mt-5 mb-5">
  <a href="indexadministrativoDatos.php" class="btn btn-primary btn-lg">Ir a Datos Administrativos</a>
</div>

<!-- MODAL REPORTES -->
<div class="modal fade" id="modalReportes" tabindex="-1" aria-labelledby="modalReportesLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalReportesLabel">📄 Reportes de Objetos rotos</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <?php
        $sql_reportes = "SELECT * FROM reportes ORDER BY fecha DESC";
        $result_reportes = $con->query($sql_reportes);
        ?>
        <?php if($result_reportes->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Objeto/Área</th>
                  <th>Descripción</th>
                  <th>Fecha</th>
                </tr>
              </thead>
              <tbody>
                <?php while($reporte = $result_reportes->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($reporte['nombre']) ?></td>
                    <td><?= htmlspecialchars($reporte['email']) ?></td>
                    <td><?= htmlspecialchars($reporte['objeto']) ?></td>
                    <td><?= nl2br(htmlspecialchars($reporte['descripcion'])) ?></td>
                    <td><?= htmlspecialchars($reporte['fecha']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-center mb-0">No hay reportes enviados aún.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL RESERVAS -->
<?php
$sql_reservas = "SELECT r.id_reserva, r.fecha, r.hora_inicio, r.hora_fin, 
                        r.aula, r.nombre AS docente, g.nombre AS grupo
                 FROM reserva r
                 LEFT JOIN grupo g ON r.grupo = g.id_grupo
                 ORDER BY r.fecha DESC, r.hora_inicio ASC";
$result_reservas = $con->query($sql_reservas);
?>
<div class="modal fade" id="modalReservas" tabindex="-1" aria-labelledby="modalReservasLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalReservasLabel">📅 Reservas de Aulas</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <?php if($result_reservas && $result_reservas->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Docente (Cédula)</th>
                  <th>Aula</th>
                  <th>Grupo</th>
                  <th>Fecha</th>
                  <th>Hora Inicio</th>
                  <th>Hora Fin</th>
                </tr>
              </thead>
              <tbody>
                <?php while($reserva = $result_reservas->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($reserva['docente']) ?></td>
                    <td><?= htmlspecialchars($reserva['aula']) ?></td>
                    <td><?= htmlspecialchars($reserva['grupo'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($reserva['fecha']) ?></td>
                    <td><?= htmlspecialchars($reserva['hora_inicio']) ?></td>
                    <td><?= htmlspecialchars($reserva['hora_fin']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-center">No hay reservas registradas aún.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FORMULARIOS -->
<?php
// Aquí irían todos los formularios tipo form-docente, form-asignatura, form-horario, etc.
// Se mantienen igual que en tu código original
?>

<?php require("footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function mostrarReportes() {
      const modal = new bootstrap.Modal(document.getElementById('modalReportes'));
      modal.show();
    }
    function mostrarReservas() {
      const modal = new bootstrap.Modal(document.getElementById('modalReservas'));
      modal.show();
    }
    window.mostrarReportes = mostrarReportes;
    window.mostrarReservas = mostrarReservas;
});
</script>
</body>
</html>

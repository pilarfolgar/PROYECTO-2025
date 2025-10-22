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
  <title>Panel Administrativo - Gestión de Datos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="styleindexadministrativoDatos.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>

<main class="contenedor">
  <h1 class="mb-4">📊 Gestión de Datos</h1>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs mb-4" id="gestionTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="docentes-tab" data-bs-toggle="tab" data-bs-target="#docentes" type="button" role="tab">Docentes</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="asignaturas-tab" data-bs-toggle="tab" data-bs-target="#asignaturas" type="button" role="tab">Asignaturas</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="horarios-tab" data-bs-toggle="tab" data-bs-target="#horarios" type="button" role="tab">Horarios</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas" type="button" role="tab">Reservas</button>
    </li>
  </ul>

  <!-- Tab content -->
  <div class="tab-content" id="gestionTabsContent">

    <!-- DOCENTES -->
    <div class="tab-pane fade show active" id="docentes" role="tabpanel">
      <?php
      $sql = "SELECT * FROM usuario WHERE rol='docente' ORDER BY nombrecompleto";
      $result = $con->query($sql);
      if($result && $result->num_rows > 0):
      ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Cédula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['cedula'] ?></td>
              <td><?= htmlspecialchars($row['nombrecompleto']) ?></td>
              <td><?= htmlspecialchars($row['apellido']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['telefono']) ?></td>
              <td>
                <a href="editar-docente.php?cedula=<?= $row['cedula'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="eliminar-docente.php?cedula=<?= $row['cedula'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este docente?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p>No hay docentes registrados.</p>
      <?php endif; ?>
    </div>

    <!-- ASIGNATURAS -->
    <div class="tab-pane fade" id="asignaturas" role="tabpanel">
      <?php
      $sql = "SELECT a.*, GROUP_CONCAT(u.nombrecompleto,' ',u.apellido SEPARATOR ', ') AS docentes_asignados
              FROM asignatura a
              LEFT JOIN docente_asignatura da ON a.id_asignatura = da.id_asignatura
              LEFT JOIN usuario u ON da.cedula_docente = u.cedula
              GROUP BY a.id_asignatura
              ORDER BY a.nombre";
      $result = $con->query($sql);
      if($result && $result->num_rows > 0):
      ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Código</th>
              <th>Docentes</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id_asignatura'] ?></td>
              <td><?= htmlspecialchars($row['nombre']) ?></td>
              <td><?= htmlspecialchars($row['codigo']) ?></td>
              <td><?= htmlspecialchars($row['docentes_asignados'] ?? '—') ?></td>
              <td>
                <a href="editar-asignatura.php?id=<?= $row['id_asignatura'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="eliminar-asignatura.php?id=<?= $row['id_asignatura'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta asignatura?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p>No hay asignaturas registradas.</p>
      <?php endif; ?>
    </div>

    <!-- HORARIOS -->
    <div class="tab-pane fade" id="horarios" role="tabpanel">
      <?php
      // Obtener todos los grupos
      $grupos = $con->query("SELECT DISTINCT id_grupo, nombre FROM grupo ORDER BY nombre");
      if($grupos && $grupos->num_rows > 0):
        while($g = $grupos->fetch_assoc()):
          $id_grupo = $g['id_grupo'];
          $nombre_grupo = $g['nombre'];

          // Obtener horarios por grupo
          $sql = "SELECT h.*, a.nombre AS asignatura
                  FROM horarios h
                  LEFT JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                  WHERE h.id_grupo = $id_grupo
                  ORDER BY h.dia, h.hora_inicio";
          $horarios = $con->query($sql);
          if($horarios && $horarios->num_rows > 0):
      ?>
      <h5 class="mt-4">Grupo: <?= htmlspecialchars($nombre_grupo) ?></h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Asignatura</th>
              <th>Día</th>
              <th>Hora Inicio</th>
              <th>Hora Fin</th>
              <th>Clase</th>
              <th>Aula</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = $horarios->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id_horario'] ?></td>
              <td><?= htmlspecialchars($row['asignatura'] ?? '—') ?></td>
              <td><?= $row['dia'] ?></td>
              <td><?= $row['hora_inicio'] ?></td>
              <td><?= $row['hora_fin'] ?></td>
              <td><?= htmlspecialchars($row['clase']) ?></td>
              <td><?= htmlspecialchars($row['aula']) ?></td>
              <td>
                <a href="editar-horario.php?id=<?= $row['id_horario'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="eliminar-horario.php?id=<?= $row['id_horario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este horario?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php
          endif;
        endwhile;
      else:
        echo "<p>No hay horarios registrados.</p>";
      endif;
      ?>
    </div>

    <!-- RESERVAS -->
    <div class="tab-pane fade" id="reservas" role="tabpanel">
      <?php
      $sql = "SELECT id_reserva, nombre, fecha, hora_inicio, hora_fin, aula, grupo FROM reserva ORDER BY fecha, hora_inicio";
      $result = $con->query($sql);
      if($result && $result->num_rows > 0):
      ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Aula</th>
              <th>Grupo</th>
              <th>Fecha</th>
              <th>Hora Inicio</th>
              <th>Hora Fin</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id_reserva'] ?></td>
              <td><?= htmlspecialchars($row['nombre']) ?></td>
              <td><?= htmlspecialchars($row['aula']) ?></td>
              <td><?= htmlspecialchars($row['grupo']) ?></td>
              <td><?= $row['fecha'] ?></td>
              <td><?= $row['hora_inicio'] ?></td>
              <td><?= $row['hora_fin'] ?></td>
              <td>
                <a href="editar-reserva.php?id=<?= $row['id_reserva'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="eliminar-reserva.php?id=<?= $row['id_reserva'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta reserva?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p>No hay reservas registradas.</p>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php
// SweetAlert mensajes
$tipos = ['aula', 'grupo', 'docente', 'horario', 'asignatura', 'reserva'];
foreach ($tipos as $tipo) {
    if (isset($_SESSION["msg_$tipo"])) {
        $mensaje = $_SESSION["msg_$tipo"];
        echo "<script>Swal.fire({icon:'success', title:'Éxito', text:'$mensaje', confirmButtonColor:'#3085d6'});</script>";
        unset($_SESSION["msg_$tipo"]);
    }
    if (isset($_SESSION["error_$tipo"])) {
        $mensaje = $_SESSION["error_$tipo"];
        echo "<script>Swal.fire({icon:'error', title:'Error', text:'$mensaje', confirmButtonColor:'#d33'});</script>";
        unset($_SESSION["error_$tipo"]);
    }
}
?>

<?php require("footer.php"); ?>
</body>
</html>

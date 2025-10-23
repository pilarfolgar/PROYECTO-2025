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

  <!-- BOTONES COLAPSABLES -->
  <div class="mb-4 d-flex flex-wrap gap-2">
    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#docentesCollapse">Docentes</button>
    <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#asignaturasCollapse">Asignaturas</button>
    <button class="btn btn-warning" data-bs-toggle="collapse" data-bs-target="#horariosCollapse">Horarios</button>
    <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#reservasCollapse">Reservas</button>
    <button class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#gruposCollapse">Grupos</button>
  </div>

  <div class="accordion" id="accordionPanels">
    <!-- DOCENTES -->
    <div class="collapse mb-5" id="docentesCollapse" data-bs-parent="#accordionPanels">
      <section>
        <h3>Docentes</h3>
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
                <td data-label="Cédula"><?= $row['cedula'] ?></td>
                <td data-label="Nombre"><?= htmlspecialchars($row['nombrecompleto']) ?></td>
                <td data-label="Apellido"><?= htmlspecialchars($row['apellido']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Teléfono"><?= htmlspecialchars($row['telefono']) ?></td>
                <td data-label="Acciones">
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
      </section>
    </div>

    <!-- ASIGNATURAS -->
    <div class="collapse mb-5" id="asignaturasCollapse" data-bs-parent="#accordionPanels">
      <section>
        <h3>Asignaturas</h3>
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
      </section>
    </div>

    <!-- HORARIOS -->
    <div class="collapse mb-5" id="horariosCollapse" data-bs-parent="#accordionPanels">
      <section>
        <h3>Horarios</h3>
        <?php
        $sql = "SELECT g.id_grupo, g.nombre AS grupo_nombre
                FROM grupo g
                ORDER BY g.nombre";
        $grupos = $con->query($sql);
        if($grupos && $grupos->num_rows > 0):
          while($grupo = $grupos->fetch_assoc()):
            echo "<h5>Grupo: " . htmlspecialchars($grupo['grupo_nombre']) . "</h5>";
            $sql_hor = "SELECT h.*, a.nombre AS asignatura
                        FROM horarios h
                        LEFT JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                        WHERE h.id_grupo = " . $grupo['id_grupo'] . "
                        ORDER BY h.dia, h.hora_inicio";
            $result = $con->query($sql_hor);
            if($result && $result->num_rows > 0):
        ?>
        <div class="table-responsive mb-3">
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
            <?php while($row = $result->fetch_assoc()): ?>
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
            else:
              echo "<p>No hay horarios registrados para este grupo.</p>";
            endif;
          endwhile;
        else:
          echo "<p>No hay grupos registrados.</p>";
        endif;
        ?>
      </section>
    </div>

    <!-- RESERVAS -->
    <div class="collapse mb-5" id="reservasCollapse" data-bs-parent="#accordionPanels">
      <section>
        <h3>Reservas</h3>
        <?php
        $sql = "SELECT id_reserva, nombre, fecha, hora_inicio, hora_fin, aula, grupo
                FROM reserva
                ORDER BY fecha, hora_inicio";
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
      </section>
    </div>

    <!-- GRUPOS -->
    <div class="collapse mb-5" id="gruposCollapse" data-bs-parent="#accordionPanels">
      <section>
        <h3>Grupos</h3>
        <?php
        $sql = "SELECT * FROM grupo ORDER BY nombre";
        $result = $con->query($sql);
        if ($result && $result->num_rows > 0):
        ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Orientación</th>
                <th>Cantidad de Estudiantes</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id_grupo'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['orientacion']) ?></td>
                <td><?= htmlspecialchars($row['cantidad_estudiantes']) ?></td>
                <td>
                  <a href="editar-grupo.php?id=<?= $row['id_grupo'] ?>" class="btn btn-sm btn-primary">Editar</a>
                  <a href="eliminar-grupo.php?id=<?= $row['id_grupo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este grupo?');">Eliminar</a>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
          <p>No hay grupos registrados.</p>
        <?php endif; ?>
      </section>
    </div>
  </div>

</main>

<?php
// SweetAlert mensajes
$tipos = ['aula', 'grupo', 'docente', 'horario', 'asignatura', 'reserva'];
foreach ($tipos as $tipo) {
    if (isset($_SESSION["msg_$tipo"])) {
        $mensaje = $_SESSION["msg_$tipo"];
        echo "<script>
            Swal.fire({icon:'success', title:'Éxito', text:'$mensaje', confirmButtonColor:'#3085d6'});
        </script>";
        unset($_SESSION["msg_$tipo"]);
    }
    if (isset($_SESSION["error_$tipo"])) {
        $mensaje = $_SESSION["error_$tipo"];
        echo "<script>
            Swal.fire({icon:'error', title:'Error', text:'$mensaje', confirmButtonColor:'#d33'});
        </script>";
        unset($_SESSION["error_$tipo"]);
    }
}
?>

<?php require("footer.php"); ?>
</body>
</html>

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
  <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleindexadministrativo.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php require("header.php"); ?>

<main class="contenedor">
  <h1 class="mb-4">📊 Gestión de Datos</h1>

  <!-- DOCENTES -->
  <section class="mb-5">
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
  </section>

  <!-- ASIGNATURAS -->
  <section class="mb-5">
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

  <!-- HORARIOS -->
  <section class="mb-5">
    <h3>Horarios</h3>
    <?php
    $sql = "SELECT h.*, a.nombre AS asignatura, g.nombre AS grupo
            FROM horarios h
            LEFT JOIN asignatura a ON h.id_asignatura = a.id_asignatura
            LEFT JOIN grupo g ON h.id_grupo = g.id_grupo
            ORDER BY h.dia, h.hora_inicio";
    $result = $con->query($sql);
    if($result && $result->num_rows > 0):
    ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Asignatura</th>
            <th>Día</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Grupo</th>
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
            <td><?= htmlspecialchars($row['grupo'] ?? '—') ?></td>
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
    <?php else: ?>
      <p>No hay horarios registrados.</p>
    <?php endif; ?>
  </section>

  <!-- AULAS -->
<section class="mb-5">
  <h3>Aulas</h3>
  <?php
  $sql = "SELECT a.*, 
                 GROUP_CONCAT(r.nombre SEPARATOR ', ') AS recursos
          FROM aula a
          LEFT JOIN aula_recurso ar ON a.id_aula = ar.id_aula
          LEFT JOIN recurso r ON ar.id_recurso = r.id_recurso
          GROUP BY a.id_aula
          ORDER BY a.codigo";
  $result = $con->query($sql);
  if($result && $result->num_rows > 0):
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Código</th>
          <th>Capacidad</th>
          <th>Ubicación</th>
          <th>Tipo</th>
          <th>Recursos</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['codigo']) ?></td>
          <td><?= htmlspecialchars($row['capacidad']) ?></td>
          <td><?= htmlspecialchars($row['ubicacion']) ?></td>
          <td><?= htmlspecialchars($row['tipo']) ?></td>
          <td><?= htmlspecialchars($row['recursos'] ?? '—') ?></td>
          <td>
            <a href="editar-aula.php?codigo=<?= urlencode($row['codigo']) ?>" class="btn btn-sm btn-primary">Editar</a>
            <a href="eliminar-aula.php?codigo=<?= urlencode($row['codigo']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta aula?');">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p>No hay aulas registradas.</p>
  <?php endif; ?>
</section>


  <!-- GRUPOS -->
  <section class="mb-5">
    <h3>Grupos</h3>
    <?php
    $sql = "SELECT g.*, GROUP_CONCAT(a.nombre SEPARATOR ', ') AS asignaturas
            FROM grupo g
            LEFT JOIN grupo_asignatura ga ON g.id_grupo = ga.id_grupo
            LEFT JOIN asignatura a ON ga.id_asignatura = a.id_asignatura
            GROUP BY g.id_grupo
            ORDER BY g.nombre";
    $result = $con->query($sql);
    if($result && $result->num_rows > 0):
    ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Orientación</th>
            <th>Cant. Estudiantes</th>
            <th>Asignaturas</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id_grupo'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['orientacion']) ?></td>
            <td><?= $row['cantidad_estudiantes'] ?></td>
            <td><?= htmlspecialchars($row['asignaturas'] ?? '—') ?></td>
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

</main>

<?php require("footer.php"); ?>
</body>
</html>

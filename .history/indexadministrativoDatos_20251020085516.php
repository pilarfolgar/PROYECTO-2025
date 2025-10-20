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
            <td data-label="ID"><?= $row['id_asignatura'] ?></td>
            <td data-label="Nombre"><?= htmlspecialchars($row['nombre']) ?></td>
            <td data-label="Código"><?= htmlspecialchars($row['codigo']) ?></td>
            <td data-label="Docentes"><?= htmlspecialchars($row['docentes_asignados'] ?? '—') ?></td>
            <td data-label="Acciones">
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
            <td data-label="ID"><?= $row['id_horario'] ?></td>
            <td data-label="Asignatura"><?= htmlspecialchars($row['asignatura'] ?? '—') ?></td>
            <td data-label="Día"><?= $row['dia'] ?></td>
            <td data-label="Hora Inicio"><?= $row['hora_inicio'] ?></td>
            <td data-label="Hora Fin"><?= $row['hora_fin'] ?></td>
            <td data-label="Grupo"><?= htmlspecialchars($row['grupo'] ?? '—') ?></td>
            <td data-label="Clase"><?= htmlspecialchars($row['clase']) ?></td>
            <td data-label="Aula"><?= htmlspecialchars($row['aula']) ?></td>
            <td data-label="Acciones">
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

  <!-- Repite el mismo patrón para AULAS y GRUPOS, agregando data-label en cada td -->
</main>

<?php
// SweetAlert mensajes
$tipos = ['aula', 'grupo', 'docente', 'horario', 'asignatura'];
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

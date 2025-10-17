<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Asignaturas con docentes
$sql_asignaturas = "SELECT a.id_asignatura, a.nombre, a.codigo, 
        GROUP_CONCAT(u.nombrecompleto, ' ', u.apellido SEPARATOR ', ') AS docentes
        FROM asignatura a
        LEFT JOIN docente_asignatura da ON a.id_asignatura = da.id_asignatura
        LEFT JOIN usuario u ON da.cedula_docente = u.cedula
        GROUP BY a.id_asignatura
        ORDER BY a.nombre";
$result_asignaturas = $con->query($sql_asignaturas);

// Docentes
$sql_docentes = "SELECT * FROM usuario WHERE rol='docente' ORDER BY nombrecompleto";
$result_docentes = $con->query($sql_docentes);

// Aulas
$sql_aulas = "SELECT * FROM aula ORDER BY codigo";
$result_aulas = $con->query($sql_aulas);

// Grupos
$sql_grupos = "SELECT g.*, GROUP_CONCAT(a.nombre SEPARATOR ', ') AS asignaturas
               FROM grupo g
               LEFT JOIN grupo_asignatura ga ON g.id_grupo = ga.id_grupo
               LEFT JOIN asignatura a ON ga.id_asignatura = a.id_asignatura
               GROUP BY g.id_grupo
               ORDER BY g.nombre";
$result_grupos = $con->query($sql_grupos);

// Horarios
$sql_horarios = "SELECT h.*, a.nombre AS asignatura, g.nombre AS grupo
                 FROM horario h
                 LEFT JOIN asignatura a ON h.id_asignatura = a.id_asignatura
                 LEFT JOIN grupo g ON h.id_grupo = g.id_grupo
                 ORDER BY h.dia, h.hora_inicio";
$result_horarios = $con->query($sql_horarios);
?>

<h2>Asignaturas</h2>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>Nombre</th>
      <th>Código</th>
      <th>Docentes</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php while($asignatura = $result_asignaturas->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($asignatura['nombre']) ?></td>
        <td><?= htmlspecialchars($asignatura['codigo']) ?></td>
        <td><?= htmlspecialchars($asignatura['docentes'] ?? '—') ?></td>
        <td>
          <a href="editar-asignatura.php?id=<?= $asignatura['id_asignatura'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
          <a href="eliminar-asignatura.php?id=<?= $asignatura['id_asignatura'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar esta asignatura?');">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<h2>Docentes</h2>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Cédula</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php while($docente = $result_docentes->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($docente['nombrecompleto']) ?></td>
        <td><?= htmlspecialchars($docente['apellido']) ?></td>
        <td><?= htmlspecialchars($docente['cedula']) ?></td>
        <td><?= htmlspecialchars($docente['email']) ?></td>
        <td><?= htmlspecialchars($docente['telefono']) ?></td>
        <td>
          <a href="editar-docente.php?id=<?= $docente['cedula'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
          <a href="eliminar-docente.php?id=<?= $docente['cedula'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este docente?');">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<!-- Repetir similar para Aulas, Grupos y Horarios -->

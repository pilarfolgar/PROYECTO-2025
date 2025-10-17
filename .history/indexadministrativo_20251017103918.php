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
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="admin-script.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<main class="contenedor py-4">

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="agregar-tab" data-bs-toggle="tab" data-bs-target="#agregar" type="button" role="tab">➕ Agregar</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="modificar-tab" data-bs-toggle="tab" data-bs-target="#modificar" type="button" role="tab">✏️ Modificar / 🗑️ Eliminar</button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="adminTabsContent">
    <!-- AGREGAR -->
    <div class="tab-pane fade show active" id="agregar" role="tabpanel">
      <div class="row g-4">
        <!-- Tarjetas de Agregar -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Docentes</h5>
              <p class="card-text">Registrar nuevos docentes.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-docente')">➕ Agregar Docente</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Asignaturas</h5>
              <p class="card-text">Registrar nuevas asignaturas.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-asignatura')">➕ Agregar Asignatura</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Horarios</h5>
              <p class="card-text">Registrar nuevos horarios.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-horario')">➕ Agregar Horario</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Aulas</h5>
              <p class="card-text">Registrar nuevas aulas.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-aula')">➕ Agregar Aula</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Grupos</h5>
              <p class="card-text">Registrar nuevos grupos.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-grupo')">➕ Agregar Grupo</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Notificaciones</h5>
              <p class="card-text">Enviar notificaciones a grupos.</p>
              <button class="btn btn-primary w-100" onclick="mostrarForm('form-notificacion')">➕ Enviar Notificación</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODIFICAR / ELIMINAR -->
    <div class="tab-pane fade" id="modificar" role="tabpanel">
      
      <!-- Docentes -->
      <h4 class="mt-3 mb-2">Docentes</h4>
      <div class="table-responsive mb-4">
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
            <?php
            $sql_docentes = "SELECT * FROM usuario WHERE rol='docente' ORDER BY nombrecompleto";
            $result_docentes = $con->query($sql_docentes);
            while($docente = $result_docentes->fetch_assoc()):
            ?>
            <tr>
              <td><?= htmlspecialchars($docente['cedula']) ?></td>
              <td><?= htmlspecialchars($docente['nombrecompleto']) ?></td>
              <td><?= htmlspecialchars($docente['apellido']) ?></td>
              <td><?= htmlspecialchars($docente['email']) ?></td>
              <td><?= htmlspecialchars($docente['telefono']) ?></td>
              <td>
                <a href="editar-docente.php?cedula=<?= $docente['cedula'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="eliminar-docente.php?cedula=<?= $docente['cedula'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este docente?');">🗑️ Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Asignaturas -->
      <h4 class="mt-3 mb-2">Asignaturas</h4>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Docentes Asignados</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql_asignaturas = "SELECT a.*, GROUP_CONCAT(CONCAT(u.nombrecompleto,' ',u.apellido) SEPARATOR ', ') AS docentes 
                                FROM asignatura a
                                LEFT JOIN asignatura_docente ad ON a.id_asignatura = ad.id_asignatura
                                LEFT JOIN usuario u ON ad.cedula_docente = u.cedula
                                GROUP BY a.id_asignatura ORDER BY a.nombre";
            $result_asignaturas = $con->query($sql_asignaturas);
            while($asignatura = $result_asignaturas->fetch_assoc()):
            ?>
            <tr>
              <td><?= htmlspecialchars($asignatura['codigo']) ?></td>
              <td><?= htmlspecialchars($asignatura['nombre']) ?></td>
              <td><?= htmlspecialchars($asignatura['docentes'] ?? '—') ?></td>
              <td>
                <a href="editar-asignatura.php?id=<?= $asignatura['id_asignatura'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="eliminar-asignatura.php?id=<?= $asignatura['id_asignatura'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar esta asignatura?');">🗑️ Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Aulas -->
      <h4 class="mt-3 mb-2">Aulas</h4>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Código</th>
              <th>Capacidad</th>
              <th>Ubicación</th>
              <th>Tipo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql_aulas = "SELECT * FROM aula ORDER BY codigo";
            $result_aulas = $con->query($sql_aulas);
            while($aula = $result_aulas->fetch_assoc()):
            ?>
            <tr>
              <td><?= htmlspecialchars($aula['codigo']) ?></td>
              <td><?= htmlspecialchars($aula['capacidad']) ?></td>
              <td><?= htmlspecialchars($aula['ubicacion']) ?></td>
              <td><?= htmlspecialchars($aula['tipo']) ?></td>
              <td>
                <a href="editar-aula.php?id=<?= $aula['id_aula'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="eliminar-aula.php?id=<?= $aula['id_aula'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar esta aula?');">🗑️ Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Horarios -->
      <h4 class="mt-3 mb-2">Horarios</h4>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Asignatura</th>
              <th>Docente</th>
              <th>Grupo</th>
              <th>Día</th>
              <th>Hora Inicio</th>
              <th>Hora Fin</th>
              <th>Aula</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql_horarios = "SELECT h.*, a.nombre AS asignatura, CONCAT(u.nombrecompleto,' ',u.apellido) AS docente, g.nombre AS grupo
                             FROM horario h
                             LEFT JOIN asignatura a ON h.id_asignatura=a.id_asignatura
                             LEFT JOIN usuario u ON h.cedula_docente=u.cedula
                             LEFT JOIN grupo g ON h.id_grupo=g.id_grupo
                             ORDER BY h.dia, h.hora_inicio";
            $result_horarios = $con->query($sql_horarios);
            while($h = $result_horarios->fetch_assoc()):
            ?>
            <tr>
              <td><?= htmlspecialchars($h['asignatura']) ?></td>
              <td><?= htmlspecialchars($h['docente']) ?></td>
              <td><?= htmlspecialchars($h['grupo'] ?? '—') ?></td>
              <td><?= htmlspecialchars($h['dia']) ?></td>
              <td><?= htmlspecialchars($h['hora_inicio']) ?></td>
              <td><?= htmlspecialchars($h['hora_fin']) ?></td>
              <td><?= htmlspecialchars($h['aula']) ?></td>
              <td>
                <a href="editar-horario.php?id=<?= $h['id_horario'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="eliminar-horario.php?id=<?= $h['id_horario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este horario?');">🗑️ Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Grupos -->
      <h4 class="mt-3 mb-2">Grupos</h4>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Nombre</th>
              <th>Orientación</th>
              <th>Cantidad Estudiantes</th>
              <th>Asignaturas</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql_grupos = "SELECT g.*, GROUP_CONCAT(a.nombre SEPARATOR ', ') AS asignaturas
                           FROM grupo g
                           LEFT JOIN grupo_asignatura ga ON g.id_grupo=ga.id_grupo
                           LEFT JOIN asignatura a ON ga.id_asignatura=a.id_asignatura
                           GROUP BY g.id_grupo
                           ORDER BY g.nombre";
            $result_grupos = $con->query($sql_grupos);
            while($grupo = $result_grupos->fetch_assoc()):
            ?>
            <tr>
              <td><?= htmlspecialchars($grupo['nombre']) ?></td>
              <td><?= htmlspecialchars($grupo['orientacion']) ?></td>
              <td><?= htmlspecialchars($grupo['cantidad']) ?></td>
              <td><?= htmlspecialchars($grupo['asignaturas'] ?? '—') ?></td>
              <td>
                <a href="editar-grupo.php?id=<?= $grupo['id_grupo'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="eliminar-grupo.php?id=<?= $grupo['id_grupo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este grupo?');">🗑️ Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</main>

<!-- INCLUIMOS LOS FORMULARIOS ORIGINALES -->
<?php
require("footer.php");
require("formularios.php"); // Aquí guardas todos tus <section id="form-...">
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php
    $alerts = [
        'msg_docente' => ['icon'=>'success','title'=>'¡Éxito!','text'=>'Docente registrado con éxito'],
        'error_docente'=>['icon'=>'error','title'=>'Cédula duplicada','text'=>'Ya existe un docente con esa cédula','form'=>'form-docente'],
        'msg_asignatura'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Asignatura registrada con éxito'],
        'error_asignatura'=>['icon'=>'error','title'=>'Código duplicado','text'=>'Ya existe una asignatura con ese código','form'=>'form-asignatura'],
        'msg_horario'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Horario registrado con éxito'],
        'error_horario'=>['icon'=>'error','title'=>'Horario duplicado','text'=>'Ya existe un horario registrado con estos datos','form'=>'form-horario'],
        'msg_aula'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Aula registrada con éxito'],
        'error_aula'=>['icon'=>'error','title'=>'Error','text'=>'Ocurrió un error al registrar el aula','form'=>'form-aula'],
        'msg_notificacion'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Notificación enviada con éxito'],
        'error_notificacion'=>['icon'=>'error','title'=>'Error','text'=>'Ocurrió un error al enviar la notificación']
    ];
    foreach($alerts as $key=>$alert){
        if(isset($_SESSION[$key])){
            $form = isset($alert['form']) ? "mostrarForm('{$alert['form']}');" : "";
            echo $form."Swal.fire({icon:'{$alert['icon']}',title:'{$alert['title']}',text:'{$alert['text']}',timer:2500,showConfirmButton:false});";
            unset($_SESSION[$key]);
        }
    }
    ?>
});

function mostrarReportes() {
  const modal = new bootstrap.Modal(document.getElementById('modalReportes'));
  modal.show();
}

function mostrarReservas() {
  const modal = new bootstrap.Modal(document.getElementById('modalReservas'));
  modal.show();
}

function mostrarSugerencias() {
  const modal = new bootstrap.Modal(document.getElementById('modalSugerencias'));
  modal.show();
}
</script>

</body>
</html>

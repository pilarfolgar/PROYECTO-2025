<?php
session_start(); // Inicia sesión
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
</main>
<div class="tarjeta">
  <h3>Reportes de Estudiantes</h3>
  <p>Ver los reportes enviados por los estudiantes.</p>
  <button class="boton" id="btnVerReportes" onclick="mostrarReportes()">Ver Reportes</button>
</div>

<!-- Contenedor donde aparecerán los reportes -->
<div id="contenedorReportes" style="display:none; margin-top:20px;"></div>
<?php
// Consulta los reportes de la base
$sql_reportes = "SELECT * FROM reportes ORDER BY fecha DESC";
$result_reportes = $con->query($sql_reportes);
?>

<div id="listaReportes" style="display:none; margin-top:20px;">
  <?php if($result_reportes->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
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
    <p class="text-center">No hay reportes enviados aún.</p>
  <?php endif; ?>
</div>


<?php
// ============================
// RESERVAS DE AULAS
// ============================
$sql_reservas = "SELECT r.id_reserva, r.fecha, r.hora_inicio, r.hora_fin, 
                        r.aula, r.nombre AS docente, g.nombre AS grupo
                 FROM reservas r
                 LEFT JOIN grupo g ON r.grupo = g.id_grupo
                 ORDER BY r.fecha DESC, r.hora_inicio ASC";
$result_reservas = $con->query($sql_reserva);
?>

<div class="tarjeta mt-4">
  <h3>Reservas de Aulas</h3>
  <p>Listado de reservas realizadas por los docentes.</p>
  <button class="boton" id="btnVerReservas" onclick="mostrarReservas()">Ver Reservas</button>
</div>

<div id="contenedorReservas" style="display:none; margin-top:20px;">
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



<?php require("footer.php"); ?>

<!-- =====================
   FORMULARIOS
===================== -->

<!-- FORM DOCENTE -->
<section id="form-docente" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-docente')">✖</button>
  <form action="procesar-docente.php" method="POST" enctype="multipart/form-data" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Docente</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombreDocente" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombreDocente" name="nombre" required>
      </div>
      <div class="col-md-6">
        <label for="apellidoDocente" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellidoDocente" name="apellido" required>
      </div>
      <div class="col-md-6">
        <label for="documentoDocente" class="form-label">Cédula</label>
        <input type="number" class="form-control" id="documentoDocente" name="documento" required>
      </div>
      <div class="col-md-6">
        <label for="emailDocente" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="emailDocente" name="email" required>
      </div>
      <div class="col-md-6">
        <label for="telefonoDocente" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="telefonoDocente" name="telefono">
      </div>
      <div class="col-md-6">
        <label for="fotoDocente" class="form-label">Foto del docente</label>
        <input type="file" class="form-control" id="fotoDocente" name="foto" accept="image/*">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM ASIGNATURA -->
<section id="form-asignatura" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-asignatura')">✖</button>
  <form action="procesar-asignatura.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Asignatura</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombreAsignatura" class="form-label">Nombre de la asignatura</label>
        <input type="text" class="form-control" id="nombreAsignatura" name="nombre" required placeholder="Ej. Programación II">
      </div>
      <div class="col-md-6">
        <label for="codigoAsignatura" class="form-label">Código</label>
        <input type="text" class="form-control" id="codigoAsignatura" name="codigo" required placeholder="Ej. PROG201">
      </div>
      <div class="col-12">
        <label for="docentesAsignatura" class="form-label">Docentes asignados (seleccione múltiples)</label>
        <select class="form-select" id="docentesAsignatura" name="docentes[]" multiple required>
          <?php
          $sql = "SELECT cedula, nombrecompleto, apellido FROM usuario WHERE rol='docente'";
          $result = $con->query($sql);
          while($row = $result->fetch_assoc()){
              echo '<option value="'.$row['cedula'].'">Prof. '.$row['nombrecompleto'].' '.$row['apellido'].'</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM HORARIO -->
<section id="form-horario" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-horario')">✖</button>
  <form action="procesar-horario.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Horario</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="asignaturaHorario" class="form-label">Asignatura</label>
        <select class="form-select" id="asignaturaHorario" name="id_asignatura" required>
          <option value="">Seleccione asignatura...</option>
          <?php
          $sql = "SELECT id_asignatura, nombre, codigo FROM asignatura ORDER BY nombre";
          $result = $con->query($sql);
          while($row = $result->fetch_assoc()){
              echo '<option value="'.$row['id_asignatura'].'">'.$row['nombre'].' ('.$row['codigo'].')</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="diaHorario" class="form-label">Día</label>
        <select class="form-select" id="diaHorario" name="dia" required>
          <option value="">Elija...</option>
          <option value="lunes">Lunes</option>
          <option value="martes">Martes</option>
          <option value="miercoles">Miércoles</option>
          <option value="jueves">Jueves</option>
          <option value="viernes">Viernes</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="horaInicioHorario" class="form-label">Hora de inicio</label>
        <input type="time" class="form-control" id="horaInicioHorario" name="hora_inicio" required>
      </div>
      <div class="col-md-6">
        <label for="horaFinHorario" class="form-label">Hora de fin</label>
        <input type="time" class="form-control" id="horaFinHorario" name="hora_fin" required>
      </div>
      <div class="col-md-6">
        <label for="grupoHorario" class="form-label">Grupo</label>
        <select class="form-select" id="grupoHorario" name="id_grupo" required>
          <option value="">Seleccione grupo...</option>
          <?php
          $sql = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
          $result = $con->query($sql);
          if($result->num_rows>0){
              while($row = $result->fetch_assoc()){
                  echo '<option value="'.$row['id_grupo'].'">'.$row['nombre'].' - '.$row['orientacion'].'</option>';
              }
          } else {
              echo '<option value="">No hay grupos registrados</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM AULA -->
<section id="form-aula" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-aula')">✖</button>
  <form action="procesar-aula.php" method="POST" enctype="multipart/form-data" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Aula</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="codigoAula" class="form-label">Número o código de aula</label>
        <input type="text" class="form-control" id="codigoAula" name="codigo" required placeholder="Ej. Aula 101">
      </div>
      <div class="col-md-6">
        <label for="capacidadAula" class="form-label">Capacidad</label>
        <input type="number" class="form-control" id="capacidadAula" name="capacidad" min="1" placeholder="Ej. 30" required>
      </div>
      <div class="col-12">
        <label for="ubicacionAula" class="form-label">Ubicación</label>
        <input type="text" class="form-control" id="ubicacionAula" name="ubicacion" placeholder="Ej. Piso 2, Bloque A" required>
      </div>
      <div class="col-12">
        <label for="tipoAula" class="form-label">Tipo de espacio</label>
        <select class="form-select" id="tipoAula" name="tipo" required>
          <option value="" disabled selected>Seleccione tipo...</option>
          <option value="aula">Aula</option>
          <option value="salon">Salón</option>
          <option value="lab">Laboratorio</option>
        </select>
      </div>
<div class="col-12">
  <label for="recursosAula" class="form-label">Recursos disponibles</label>
  
  <!-- Selección múltiple de recursos existentes -->
  <select name="recursos_existentes[]" class="form-select" multiple size="7">
    <option value="Aire acondicionado">Aire acondicionado</option>
    <option value="Televisor">Televisor</option>
    <option value="Proyector">Proyector</option>
    <option value="Computadoras">Computadoras</option>
    <option value="Ventilador">Ventilador</option>
    <option value="Impresora 3D">Impresora 3D</option>
  </select>
  <small class="text-muted d-block mb-2">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar varios.</small>

  <!-- Input para agregar un recurso adicional -->
  <label class="form-label mt-2">Agregar recurso adicional</label>
  <input type="text" name="recurso_nuevo" class="form-control" placeholder="Ej. Pizarra digital">
  <small class="text-muted">Si escribes algo aquí, se agregará como un recurso nuevo además de los seleccionados.</small>
</div>

      <div class="col-12">
        <label for="imagenAula" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="imagenAula" name="imagen" accept="image/*">
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM GRUPO -->
<section id="form-grupo" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-grupo')">✖</button>
  <form action="procesar-grupo.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Registrar Grupo</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="orientacionGrupo" class="form-label">Orientación</label>
        <select class="form-select" id="orientacionGrupo" name="orientacion" required>
          <option value="">Seleccione orientación...</option>
          <option value="Tec. de la Información">Tecnologías de la información</option>
          <option value="Tec. de la Información Bilingüe">Tecnologías de la información Bilingüe</option>
          <option value="Tecnología">Tecnólogo en Ciberseguridad</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="nombreGrupo" class="form-label">Nombre del grupo</label>
        <input type="text" class="form-control" id="nombreGrupo" name="nombre" required placeholder="Ej. 3°A">
      </div>
      <div class="col-md-6">
        <label for="cantidadEstudiantes" class="form-label">Cantidad de estudiantes</label>
        <input type="number" class="form-control" id="cantidadEstudiantes" name="cantidad" min="1" required placeholder="Ej. 30">
      </div>
      <div class="col-md-6">
        <label for="asignaturasGrupo" class="form-label">Asignaturas</label>
        <select class="form-select" id="asignaturasGrupo" name="asignaturas[]" multiple required>
          <?php
          $sql = "SELECT id_asignatura, nombre FROM asignatura ORDER BY nombre";
          $result = $con->query($sql);
          while($row = $result->fetch_assoc()){
              echo '<option value="'.$row['id_asignatura'].'">'.$row['nombre'].'</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <button type="submit" class="boton mt-3">Guardar</button>
  </form>
</section>

<!-- FORM NOTIFICACIÓN -->
<section id="form-notificacion" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-notificacion')">✖</button>
  <form action="procesar-notificacion.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Enviar Notificación a Grupo</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="grupoNotificacion" class="form-label">Grupo</label>
        <select class="form-select" id="grupoNotificacion" name="id_grupo" required>
          <option value="">Seleccione grupo...</option>
          <?php
          $sql = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
          $result = $con->query($sql);
          while($row = $result->fetch_assoc()){
              echo '<option value="'.$row['id_grupo'].'">'.$row['nombre'].' - '.$row['orientacion'].'</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="tituloNotificacion" class="form-label">Título</label>
        <input type="text" class="form-control" id="tituloNotificacion" name="titulo" required placeholder="Ej. Cambio de aula">
      </div>
      <div class="col-12">
        <label for="mensajeNotificacion" class="form-label">Mensaje</label>
        <textarea class="form-control" id="mensajeNotificacion" name="mensaje" rows="4" required placeholder="Escriba su mensaje"></textarea>
      </div>
    </div>
    <button type="submit" class="boton mt-3">Enviar</button>
  </form>
</section>



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
  const contenedor = document.getElementById('listaReportes');
  contenedor.style.display = (contenedor.style.display === 'none' || contenedor.style.display === '') 
    ? 'block' 
    : 'none';
}

function mostrarReservas() {
  const contenedor = document.getElementById('contenedorReservas');
  contenedor.style.display = (contenedor.style.display === 'none' || contenedor.style.display === '') 
    ? 'block' 
    : 'none';
}
</script>
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

<main class="contenedor" id="gestion">
  <!-- TARJETAS DE ACCIONES -->
  <?php
  $tarjetas = [
      ['titulo'=>'Docentes','texto'=>'Registrar y actualizar datos de los docentes.','form'=>'form-docente'],
      ['titulo'=>'Asignaturas','texto'=>'Crear, modificar y administrar asignaturas.','form'=>'form-asignatura'],
      ['titulo'=>'Horarios','texto'=>'Organizar y actualizar los horarios de clases.','form'=>'form-horario'],
      ['titulo'=>'Aulas','texto'=>'Administrar aulas disponibles y asignaciones.','form'=>'form-aula'],
      ['titulo'=>'Grupos','texto'=>'Crear y administrar grupos de estudiantes.','form'=>'form-grupo'],
      ['titulo'=>'Enviar Notificación','texto'=>'Informar cambios, avisos o recordatorios a un grupo de estudiantes.','form'=>'form-notificacion'],
  ];
  foreach($tarjetas as $t){
      echo '<div class="tarjeta">
              <h3>'.$t['titulo'].'</h3>
              <p>'.$t['texto'].'</p>
              <a href="#" class="boton" onclick="mostrarForm(\''.$t['form'].'\')">➕ Agregar '.$t['titulo'].'</a>
            </div>';
  }
  ?>
</main>

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
      <div class="col-md-6"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" required></div>
      <div class="col-md-6"><label class="form-label">Apellido</label><input type="text" class="form-control" name="apellido" required></div>
      <div class="col-md-6"><label class="form-label">Cédula</label><input type="number" class="form-control" name="documento" required></div>
      <div class="col-md-6"><label class="form-label">Correo electrónico</label><input type="email" class="form-control" name="email" required></div>
      <div class="col-md-6"><label class="form-label">Teléfono</label><input type="tel" class="form-control" name="telefono"></div>
      <div class="col-md-6"><label class="form-label">Foto del docente</label><input type="file" class="form-control" name="foto" accept="image/*"></div>
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
      <div class="col-md-6"><label class="form-label">Nombre de la asignatura</label><input type="text" class="form-control" name="nombre" required placeholder="Ej. Programación II"></div>
      <div class="col-md-6"><label class="form-label">Código</label><input type="text" class="form-control" name="codigo" required placeholder="Ej. PROG201"></div>
      <div class="col-12">
        <label class="form-label">Docentes asignados</label>
        <select class="form-select" name="docentes[]" multiple required>
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
        <label class="form-label">Asignatura</label>
        <select class="form-select" name="id_asignatura" required>
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
        <label class="form-label">Día</label>
        <select class="form-select" name="dia" required>
          <option value="">Elija...</option>
          <option value="lunes">Lunes</option>
          <option value="martes">Martes</option>
          <option value="miercoles">Miércoles</option>
          <option value="jueves">Jueves</option>
          <option value="viernes">Viernes</option>
        </select>
      </div>
      <div class="col-md-6"><label class="form-label">Hora inicio</label><input type="time" class="form-control" name="hora_inicio" required></div>
      <div class="col-md-6"><label class="form-label">Hora fin</label><input type="time" class="form-control" name="hora_fin" required></div>
      <div class="col-md-6">
        <label class="form-label">Grupo</label>
        <select class="form-select" name="id_grupo" required>
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
      <div class="col-md-6"><label class="form-label">Número o código de aula</label><input type="text" class="form-control" name="codigo" required placeholder="Ej. Aula 101"></div>
      <div class="col-md-6"><label class="form-label">Capacidad</label><input type="number" class="form-control" name="capacidad" min="1" required placeholder="Ej. 30"></div>
      <div class="col-12"><label class="form-label">Ubicación</label><input type="text" class="form-control" name="ubicacion" placeholder="Ej. Piso 2, Bloque A" required></div>
      <div class="col-12">
        <label class="form-label">Tipo de espacio</label>
        <select class="form-select" name="tipo" required>
          <option value="" disabled selected>Seleccione tipo...</option>
          <option value="aula">Aula</option>
          <option value="salon">Salón</option>
          <option value="lab">Laboratorio</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Recursos disponibles</label>
        <select name="recursos_existentes[]" class="form-select" multiple size="7">
          <option value="Aire acondicionado">Aire acondicionado</option>
          <option value="Televisor">Televisor</option>
          <option value="Proyector">Proyector</option>
          <option value="Computadoras">Computadoras</option>
          <option value="Ventilador">Ventilador</option>
          <option value="Impresora 3D">Impresora 3D</option>
        </select>
        <small>Ctrl (o Cmd) para seleccionar varios</small>
        <label class="form-label mt-2">Agregar recurso adicional</label>
        <input type="text" name="recurso_nuevo" class="form-control" placeholder="Ej. Pizarra digital">
      </div>
      <div class="col-12"><label class="form-label">Imagen</label><input type="file" class="form-control" name="imagen" accept="image/*"></div>
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
        <label class="form-label">Orientación</label>
        <select class="form-select" name="orientacion" required>
          <option value="">Seleccione orientación...</option>
          <option value="Tec. de la Información">Tecnologías de la información</option>
          <option value="Tec. de la Información Bilingüe">Tecnologías de la información Bilingüe</option>
          <option value="Tecnología">Tecnólogo en Ciberseguridad</option>
        </select>
      </div>
      <div class="col-md-6"><label class="form-label">Nombre del grupo</label><input type="text" class="form-control" name="nombre" required placeholder="Ej. 3°A"></div>
      <div class="col-md-6"><label class="form-label">Cantidad de estudiantes</label><input type="number" class="form-control" name="cantidad" min="1" required placeholder="Ej. 30"></div>
      <div class="col-md-6">
        <label class="form-label">Asignaturas</label>
        <select class="form-select" name="asignaturas[]" multiple required>
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

<!-- FORM NOTIFICACION -->
<section id="form-notificacion" class="formulario" style="display:none;">
  <button type="button" class="cerrar" onclick="cerrarForm('form-notificacion')">✖</button>
  <form action="procesar-notificacion.php" method="POST" class="needs-validation form-reserva-style novalidate">
    <h2 class="form-title">Enviar Notificación a Grupo</h2>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Grupo</label>
        <select class="form-select" name="id_grupo" required>
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
      <div class="col-md-6"><label class="form-label">Título</label><input type="text" class="form-control" name="titulo" required placeholder="Ej. Cambio de aula"></div>
      <div class="col-12"><label class="form-label">Mensaje</label><textarea class="form-control" name="mensaje" rows="4" required placeholder="Escriba su mensaje"></textarea></div>
    </div>
    <button type="submit" class="boton mt-3">Enviar</button>
  </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php
    $alerts = [
        'msg_docente'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Docente registrado con éxito','form'=>'form-docente'],
        'error_docente'=>['icon'=>'error','title'=>'Cédula duplicada','text'=>'Ya existe un docente con esa cédula','form'=>'form-docente'],
        'msg_asignatura'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Asignatura registrada con éxito','form'=>'form-asignatura'],
        'error_asignatura'=>['icon'=>'error','title'=>'Código duplicado','text'=>'Ya existe una asignatura con ese código','form'=>'form-asignatura'],
        'msg_horario'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Horario registrado con éxito','form'=>'form-horario'],
        'error_horario'=>['icon'=>'error','title'=>'Horario duplicado','text'=>'Ya existe un horario registrado con estos datos','form'=>'form-horario'],
        'msg_aula'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Aula registrada con éxito','form'=>'form-aula'],
        'error_aula'=>['icon'=>'error','title'=>'Error','text'=>'Ocurrió un error al registrar el aula','form'=>'form-aula'],
        'msg_notificacion'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Notificación enviada con éxito','form'=>'form-notificacion'],
        'error_notificacion'=>['icon'=>'error','title'=>'Error','text'=>'Ocurrió un error al enviar la notificación','form'=>'form-notificacion']
    ];
    foreach($alerts as $key=>$a){
        if(isset($_SESSION[$key])){
            echo "mostrarForm('".$a['form']."');";
            echo "Swal.fire({icon:'".$a['icon']."',title:'".$a['title']."',text:'".$a['text']."',timer:2500,showConfirmButton:false});";
            unset($_SESSION[$key]);
        }
    }
    ?>
});
</script>

</body>
</html>

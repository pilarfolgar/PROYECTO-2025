<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificamos que el estudiante haya iniciado sesión
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'estudiante'){
    header("Location: iniciosesion.php");
    exit;
}

$estudiante_id = $_SESSION['usuario_id'];
$grupo_id = $_SESSION['grupo_id'];

// ====== NOTIFICACIONES DEL ESTUDIANTE ======
$notificaciones = [];
$sql_notif = "SELECT id, titulo, mensaje, fecha, visto_estudiante 
              FROM notificaciones 
              WHERE grupo_id = ? 
              ORDER BY fecha DESC";
$stmt = $con->prepare($sql_notif);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $notificaciones[] = $row;
}

// ====== AVISOS GENERALES ======
$avisos = [];
$sql_avisos = "SELECT titulo, mensaje, fecha FROM avisos ORDER BY fecha DESC";
$result = $con->query($sql_avisos);
while($row = $result->fetch_assoc()){
    $avisos[] = $row;
}

// ====== HORARIOS DEL GRUPO ======
$horarios = [];
$sql_horarios = "SELECT h.dia_semana, a.nombre AS asignatura, h.hora_inicio, h.hora_fin, h.aula
                 FROM horarios h
                 INNER JOIN asignaturas a ON h.id_asignatura = a.id
                 WHERE h.grupo_id = ?
                 ORDER BY FIELD(dia_semana,'Lunes','Martes','Miércoles','Jueves','Viernes'), h.hora_inicio";
$stmt = $con->prepare($sql_horarios);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $horarios[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Estudiantes - InfraLex</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<header>
  <div class="HeaderIzq">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
  <div class="header-right">
    <a href="indexEstudiantes.php"><img src="imagenes/LOGO.jpeg" alt="Logo" class="logo"></a>
  </div>
</header>

<nav>
  <!-- Eliminamos materiales fijos -->
  <a href="horarios.php">Horarios de clase</a>
  <a href="index.php">Cerrar sesión</a>
</nav>

<main class="container my-5">
  <h2 class="text-center mb-4">Bienvenido/a, Estudiante</h2>

  <!-- ALERTA CON PRÓXIMA CLASE (opcional dinámica, aquí fijo) -->
  <div class="alert alert-info text-center" role="alert" id="notificacionAula">
    📢 Hoy te toca clase en: <strong>Aula 12 - Segundo piso</strong>
  </div>

  <div class="row">
    <!-- HORARIOS -->
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-light shadow-sm">
        <h4 class="text-center mb-3">Calendario de clases</h4>
        <ul class="list-group">
          <?php if(count($horarios) > 0): ?>
            <?php foreach($horarios as $h): ?>
              <li class="list-group-item">
                📅 <?php echo $h['dia_semana']; ?> - <?php echo $h['asignatura']; ?> 
                - <?php echo $h['aula']; ?> 
                (<?php echo substr($h['hora_inicio'],0,5) . " - " . substr($h['hora_fin'],0,5); ?>)
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item text-center">No hay clases asignadas.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- NOTIFICACIONES Y AVISOS -->
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-white shadow-sm">
        <h4 class="text-center mb-3">Notificaciones y avisos</h4>
        <ul class="list-group">
          <?php if(count($notificaciones) > 0): ?>
            <?php foreach($notificaciones as $n): ?>
              <li class="list-group-item">
                <strong><?php echo $n['titulo']; ?></strong><br>
                <?php echo $n['mensaje']; ?><br>
                <small class="text-muted"><?php echo date("d/m/Y H:i", strtotime($n['fecha'])); ?></small>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
          <?php if(count($avisos) > 0): ?>
            <?php foreach($avisos as $a): ?>
              <li class="list-group-item list-group-item-warning">
                <strong><?php echo $a['titulo']; ?></strong><br>
                <?php echo $a['mensaje']; ?><br>
                <small class="text-muted"><?php echo date("d/m/Y H:i", strtotime($a['fecha'])); ?></small>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
          <?php if(count($notificaciones) == 0 && count($avisos) == 0): ?>
            <li class="list-group-item text-center">No hay notificaciones ni avisos.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</main>

<!-- Botón flotante -->
<button id="btnAbrirReporte" class="btn-flotante">📝 Reportar Objeto Dañado</button>

<!-- Overlay -->
<div id="overlayReporte" class="formulario-overlay"></div>

<!-- Formulario flotante -->
<section id="form-reporte" class="formulario">
  <button type="button" class="cerrar" id="btnCerrarReporte">✖</button>
  <form id="reporteForm" action="#" method="POST" class="needs-validation form-reserva-style" novalidate>

    <h2 class="form-title">Reportar Objeto Dañado</h2>

    <div class="mb-3">
      <label for="nombreReporte" class="form-label">Nombre</label>
      <input type="text" class="form-control" id="nombreReporte" name="nombre" required pattern="^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$">
      <div class="invalid-feedback">Por favor, ingrese un nombre válido (solo letras).</div>
    </div>

    <div class="mb-3">
      <label for="emailReporte" class="form-label">Email</label>
      <input type="email" class="form-control" id="emailReporte" name="email" required>
      <div class="invalid-feedback">Ingrese un correo electrónico válido.</div>
    </div>

    <div class="mb-3">
      <label for="objetoReporte" class="form-label">Objeto o área</label>
      <input type="text" class="form-control" id="objetoReporte" name="objeto" required>
      <div class="invalid-feedback">Este campo es obligatorio.</div>
    </div>

    <div class="mb-3">
      <label for="descripcionReporte" class="form-label">Descripción del problema</label>
      <textarea class="form-control" id="descripcionReporte" name="descripcion" rows="3" minlength="10" required></textarea>
      <div class="invalid-feedback">La descripción debe tener al menos 10 caracteres.</div>
    </div>

    <div class="mb-3">
      <label for="fechaReporte" class="form-label">Fecha del reporte</label>
      <input type="date" class="form-control" id="fechaReporte" name="fecha" required>
      <div class="invalid-feedback">Seleccione una fecha válida (no futura).</div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Enviar Reporte</button>
    <div id="mensajeReporte" class="mt-3 text-center"></div>
  </form>
</section>
<!-- ================= Funcionalidades Extra para Estudiantes ================= -->
<div class="row my-4">

  <!-- Temporizador de estudio -->
  <div class="col-md-6 mb-4">
    <div class="p-3 border rounded bg-light shadow-sm">
      <h4 class="text-center mb-3">⏱️ Temporizador de estudio</h4>
      <div class="text-center">
        <span id="timer">25:00</span>
        <div class="mt-2">
          <button id="startTimer" class="btn btn-success btn-sm">Iniciar</button>
          <button id="pauseTimer" class="btn btn-warning btn-sm">Pausar</button>
          <button id="resetTimer" class="btn btn-danger btn-sm">Reiniciar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Notas rápidas -->
  <div class="col-md-6 mb-4">
    <div class="p-3 border rounded bg-white shadow-sm">
      <h4 class="text-center mb-3">📝 Notas rápidas</h4>
      <textarea id="quickNotes" class="form-control" rows="6" placeholder="Escribe tus notas..."></textarea>
      <button id="saveNotes" class="btn btn-primary btn-sm mt-2">Guardar notas</button>
      <p id="notesMsg" class="mt-2 text-success"></p>
    </div>
  </div>

</div>



<footer class="footer mt-5">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú | Contacto: evolutionit2008@gmail.com
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>
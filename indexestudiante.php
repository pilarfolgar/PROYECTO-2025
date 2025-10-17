<?php 
require("seguridad.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Estudiante</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="styleestudiante.css">
</head>
<body>
<?php require("header.php"); ?>

<section class="mis-cursos my-5">
  <h2 class="text-center mb-4">Panel Estudiante</h2>
  <div class="docentes-grid">

    <!-- Tarjeta Notificaciones -->
    <div class="estudiante-card">
      <div class="docente-photo bg-primary text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-bell"></i>
      </div>
      <div class="docente-name">Notificaciones</div>
      <div class="docente-subject">Ver tus avisos importantes</div>
      <a href="notificaciones.php" class="boton w-100 text-center">Ir a Notificaciones</a>
    </div>

    <!-- Tarjeta Avisos -->
    <div class="estudiante-card">
      <div class="docente-photo bg-success text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-clipboard"></i>
      </div>
      <div class="docente-name">Avisos</div>
      <div class="docente-subject">Consulta los avisos generales</div>
      <a href="avisos.php" class="boton w-100 text-center">Ir a Avisos</a>
    </div>

    <!-- Tarjeta Horario -->
    <div class="estudiante-card">
      <div class="docente-photo bg-warning text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-calendar-week"></i>
      </div>
      <div class="docente-name">Horario del Grupo</div>
      <div class="docente-subject">Visualiza tus clases y aulas</div>
      <a href="horarios.php" class="boton w-100 text-center">Ver Horario</a>
    </div>

    <!-- Tarjeta Enviar Sugerencia -->
    <div class="estudiante-card">
      <div class="docente-photo bg-danger text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-pencil-square"></i>
      </div>
      <div class="docente-name">Enviar Sugerencia</div>
      <div class="docente-subject">Reporta un problema o sugerencia</div>
      <button class="boton w-100 text-center" id="abrirSugerencia">Enviar</button>
    </div>

  </div>
</section>

<!-- Overlay sugerencia -->
<!-- Overlay sugerencia -->
<div id="overlaySugerenciaDiv" class="formulario-overlay"></div>


<!-- Formulario flotante sugerencia -->
<section id="form-sugerencia" class="formulario">
  <button type="button" class="cerrar" id="cerrarSugerencia">✖</button>
 <form id="sugerenciaForm" method="POST" action="guardar-sugerencia.php">

    <h2 class="form-title">Sugerencia</h2>
    <div class="mb-3">
      <textarea class="form-control" id="mensajeSugerencia" name="mensaje" rows="4" minlength="5" required placeholder="Escribí tu mensaje"></textarea>
      <div class="invalid-feedback">Debe escribir al menos 5 caracteres.</div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Enviar</button>
  </form>
</section>

<!-- Botón flotante Reporte -->
<button id="btnAbrirReporte" class="btn-flotante">📝 Reportar Objeto Dañado</button>

<!-- Overlay reporte -->
<div id="overlayReporte" class="formulario-overlay"></div>

<!-- Formulario flotante reporte -->
<section id="form-reporte" class="formulario">
  <button type="button" class="cerrar" id="btnCerrarReporte">✖</button>
  <form id="reporteForm" action="guardar-reporte-.php" method="POST" class="needs-validation form-reserva-style" novalidate>
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

<?php require("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>


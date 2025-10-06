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
<?php
require("conexion.php");
session_start();
$con = conectar_bd();
$cedula = $_SESSION['cedula'] ?? 0;

// Contar notificaciones no leídas
$stmt = $con->prepare("SELECT COUNT(*) AS sin_leer FROM notificacion WHERE id_usuario = ? AND leida = 0");
$stmt->bind_param("i", $cedula);
$stmt->execute();
$result = $stmt->get_result();
$sin_leer = $result->fetch_assoc()['sin_leer'];

// Traer últimas 5 notificaciones
$stmt2 = $con->prepare("SELECT * FROM notificacion WHERE id_usuario = ? ORDER BY fecha DESC LIMIT 5");
$stmt2->bind_param("i", $cedula);
$stmt2->execute();
$result2 = $stmt2->get_result();
$notificaciones = $result2->fetch_all(MYSQLI_ASSOC);
?>

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
  <a href="materiales.php">Material de estudio</a>
  <a href="horarios.php">Horarios de clase</a>
  <a href="index.php">Cerrar sesión</a>
</nav>

<main class="container my-5">
  <h2 class="text-center mb-4">Bienvenido/a, Estudiante</h2>

  <div class="alert alert-info text-center" role="alert" id="notificacionAula">
    📢 Hoy te toca clase en: <strong>Aula 12 - Segundo piso</strong>
  </div>

  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-light shadow-sm">
        <h4 class="text-center mb-3">Calendario de clases</h4>
        <ul class="list-group">
          <li class="list-group-item">📅 Lunes - Programación - Aula 11</li>
          <li class="list-group-item">📅 Martes - Redes - Aula 12</li>
          <li class="list-group-item">📅 Miércoles - Base de Datos - Aula 14</li>
          <li class="list-group-item">📅 Jueves - Diseño Web - Aula 10</li>
          <li class="list-group-item">📅 Viernes - Taller Integrador - Aula 8</li>
        </ul>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded bg-white shadow-sm">
        <h4 class="text-center mb-3">Accesos rápidos</h4>
        <ul class="list-group">
          <li class="list-group-item"><a href="https://www.utu.edu.uy/" target="_blank">🌐 Plataforma UTU</a></li>
          <li class="list-group-item"><a href="https://sites.google.com/view/classrooms-workspace/" target="_blank">📘 Google Classroom</a></li>
          <li class="list-group-item"><a href="horarios.php">📅 Ver horarios</a></li>
          <li class="list-group-item"><a href="materiales.php">📂 Material de estudio</a></li>
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

<footer class="footer mt-5">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú | Contacto: evolutionit2008@gmail.com
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="estudiantes.js"></script>
</body>
</html>

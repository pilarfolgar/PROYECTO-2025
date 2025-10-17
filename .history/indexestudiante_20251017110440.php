<?php 
session_start();
require("conexion.php"); // Conexión a la b

$cedula = $_SESSION['cedula'];
$grupoNombre = "";

// Obtener id_grupo del usuario
$stmt = $conn->prepare("SELECT id_grupo FROM usuario WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    $idGrupo = $fila['id_grupo'];

    // Obtener nombre del grupo desde tabla grupo
    $stmt2 = $conn->prepare("SELECT nombre FROM grupo WHERE id = ?");
    $stmt2->bind_param("i", $idGrupo);
    $stmt2->execute();
    $resultado2 = $stmt2->get_result();

    if ($fila2 = $resultado2->fetch_assoc()) {
        $grupoNombre = $fila2['nombre'];
    } else {
        $grupoNombre = "Grupo no encontrado";
    }
    $stmt2->close();
} else {
    $grupoNombre = "Grupo no asignado";
}
$stmt->close();
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

    <!-- Tarjeta Horario -->
    <div class="estudiante-card">
      <div class="docente-photo bg-warning text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-calendar-week"></i>
      </div>
      <div class="docente-name">Horario del Grupo</div>
      <div class="docente-subject">Visualiza tus clases y aulas</div>
      <a href="horarios.php" class="boton w-100 text-center">Ver Horario</a>
    </div>

    <!-- Tarjeta Clase del Estudiante -->
    <div class="estudiante-card">
      <div class="docente-photo bg-info text-white fs-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-people"></i>
      </div>
      <div class="docente-name">Mi Clase</div>
      <div class="docente-subject">
        <?php echo htmlspecialchars($grupoNombre); ?>
      </div>
    </div>

  </div>
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
    <div id="mensajeReporte" class="mt-3 text-center"></div

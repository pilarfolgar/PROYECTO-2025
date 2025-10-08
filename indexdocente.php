<!DOCTYPE html>
<html lang="es">
<head>


<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Panel Docentes - InfraLex</title>


<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">


<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<link rel="stylesheet" href="styleindexdocente.css" />
<link rel="stylesheet" href="style.css">
</head>
<body>


<header>
  <div class="header-left">
    <h1>InfraLex</h1>
      <img src="imagenes/logopoyecto.png" alt="Logo" class="logo" style="height:80px;width:80px;object-fit:cover;border-radius:50%;border:3px solid #588BAE;">
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
</header>


<nav class="main-nav">
  <a href="#" class="nav-link">Mis cursos</a>
  <a href="aulas.php" class="nav-link" >Reservar aulas</a>
 
</nav>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <section class="mis-cursos">
    <h2>Mis cursos</h2>
    <div class="docentes-grid">
      <div class="docente-card">
        <div class="docente-photo"></div>
        <div class="docente-name">1°MA - Lengua</div>
        <div class="docente-subject">Turno matutino </div>
        <button class="boton ver-miembros" data-clase="1°MA - Lengua">Ver miembros</button>
        <ul class="lista-miembros" style="display:none;"></ul>
      </div>
      <div class="docente-card">
        <div class="docente-photo"></div>
        <div class="docente-name">2°BB - Matemática</div>
        <div class="docente-subject">Turno vespertino </div>
        <button class="boton ver-miembros" data-clase="2°BB - Matemática">Ver miembros</button>
        <ul class="lista-miembros" style="display:none;"></ul>
      </div>
    </div>
  </section>


  <section>
    <h2>Mis reservas</h2>
    <div id="reservas-container">
      <div class="no-reservas">No hay reservas por el momento.</div>
    </div>
  </section>
</main>


<section class="container mt-5 mb-5 pt-4 pb-4 bg-light rounded-4 shadow-sm">
  <h2 class="text-center mb-4">Vista previa de Aulas</h2>
  <div class="row g-4 justify-content-center">
    <?php
      $aulas = [
        ["nombre" => "Aula 1", "capacidad" => "30 personas", "imagen" => "AULA1.jpeg"],
        ["nombre" => "Aula 2", "capacidad" => "30 personas", "imagen" => "AULA2.jpeg"],
        ["nombre" => "Aula 3", "capacidad" => "30 personas", "imagen" => "AULA3.jpeg"],
        ["nombre" => "Salón 1", "capacidad" => "100 personas", "imagen" => "SALON1 Y 2.jpeg"],
        ["nombre" => "Laboratorio de Robótica", "capacidad" => "20 computadoras", "imagen" => "ROBOTICA.jpeg"]
      ];
      foreach ($aulas as $aula) {
        echo '
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <img src="Imagenes/'.$aula["imagen"].'" class="card-img-top" alt="'.$aula["nombre"].'">
            <div class="card-body text-center">
              <h5 class="card-title">'.$aula["nombre"].'</h5>
              <p class="card-text">Capacidad: '.$aula["capacidad"].'</p>
              <a href="aulas.php" class="btn btn-primary">Reservar</a>
            </div>
          </div>
        </div>';
      }
    ?>
  </div>


  <div class="text-center mt-4">
    <a href="aulas.php" class="btn btn-outline-primary btn-lg">Ver todas las aulas</a>
  </div>
</section>


<main class="contenedor">
<!-- CALENDARIO DIARIO DE RESERVAS DE AULAS -->
<div style="width:100vw;max-width:100%;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%);background:#f0f4f8;padding:2rem 0 2rem 0;">
  <h2 class="text-center mb-4">Calendario diario de aulas</h2>
  <div class="table-responsive" style="padding:2rem;">
    <div class="calendario-scroll">
      <table class="table calendario-aulas-table align-middle text-center" style="min-width:1200px;width:100%;">
        <thead class="table-primary">
          <tr>
            <th style="width:110px;">Hora</th>
            <th>Aula 1</th>
            <th>Aula 2</th>
            <th>Aula 3</th>
            <th>Salón de Actos</th>
            <th>Salón 1</th>
            <th>Salón 2</th>
            <th>Salón 3</th>
            <th>Salón 4</th>
            <th>Salón 5</th>
            <th>Lab. Robótica</th>
            <th>Lab. Química</th>
            <th>Lab. Física</th>
            <th>Taller de Mantenimiento</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Generar bloques de 45 minutos desde 7:00 a 23:00
          function sumarMinutos($hora, $minutos) {
            $h = (int)substr($hora,0,2);
            $m = (int)substr($hora,3,2);
            $m += $minutos;
            $h += intdiv($m,60);
            $m = $m % 60;
            return sprintf('%02d:%02d', $h, $m);
          }
          $horaInicio = "07:00";
          $horaFin = "23:00";
          $bloques = [];
          $h = $horaInicio;
          while ($h < $horaFin) {
            $bloques[] = $h;
            $h = sumarMinutos($h, 45);
          }
          $horas = $bloques;
          $aulas = [
            "Aula 1", "Aula 2", "Aula 3",
            "Salón de Actos", "Salón 1", "Salón 2", "Salón 3", "Salón 4", "Salón 5",
            "Lab. Robótica", "Lab. Química", "Lab. Física", "Taller de Mantenimiento"
          ];
          $reservas = [
            "Aula 1" => ["08:30", "13:15", "18:00"],
            "Aula 2" => ["09:15", "15:45"],
            "Aula 3" => ["10:00", "20:15"],
            "Salón de Actos" => ["07:00", "09:15", "11:00"],
            "Salón 1" => ["07:00", "11:45", "21:30"],
            "Salón 2" => ["08:30", "14:30"],
            "Salón 3" => ["10:45", "16:00"],
            "Salón 4" => ["12:15", "18:45"],
            "Salón 5" => ["13:15", "20:00"],
            "Lab. Robótica" => ["14:30", "16:45"],
            "Lab. Química" => ["09:15", "17:30"],
            "Lab. Física" => ["11:00", "19:15"],
            "Taller de Mantenimiento" => ["15:00", "21:00"]
          ];
          foreach ($horas as $hora) {
            // ...existing code...
            echo '<tr>';
            echo '<td><strong>'.$hora.'</strong></td>';
            foreach ($aulas as $aula) {
              if (in_array($hora, $reservas[$aula])) {
                echo '<td class="bg-gradient bg-danger text-white"><span title="Reservado"><span class="badge rounded-pill bg-danger" style="font-size:1em;padding:0.6em 1.2em"><i class="bi bi-x-circle-fill"></i></span></td>';
              } else {
                echo '<td class="bg-gradient bg-success text-dark"><span title="Disponible"><span class="badge rounded-pill bg-success" style="font-size:1em;padding:0.6em 1.2em"><i class="bi bi-check-circle-fill"></i></span></td>';
              }
            }
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    <div class="mt-3 text-start">
  <span class="badge bg-success" style="background:#A2D5F2;color:#1B3A4B;"><i class="bi bi-check-circle-fill"></i> Disponible</span>
  <span class="badge bg-danger ms-2" style="background:#ff6b6b;color:#fff;"><i class="bi bi-x-circle-fill"></i> Reservado</span>
    </div>
  </div>
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



<footer class="footer">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú
</footer>




<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="docentes.js"></script>
<script src="estudiantes.js"></script>

</body>
</html>



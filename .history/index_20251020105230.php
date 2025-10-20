<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Traer solo 3 docentes ordenados por nombre
$sql = "SELECT nombrecompleto, apellido, email, telefono, foto, asignatura 
        FROM usuario 
        WHERE rol = 'docente' 
        ORDER BY nombrecompleto 
        LIMIT 3";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Evolution IT</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<?php require("HeaderIndex.php"); ?>

<!-- CARRUSEL -->
<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <img src="imagenes/frenteitsp.jpg" class="d-block w-100" alt="Fachada ITSP">
      <div class="carousel-caption d-none d-md-block">
        <h2>Bienvenido a ITSP</h2>
        <p>Educación técnica de calidad desde 1975</p>
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="8000">
      <img src="imagenes/patio.jpeg" class="d-block w-100" alt="Patio">
    </div>
    <div class="carousel-item">
      <img src="imagenes/biblioteca.jpeg" class="d-block w-100" alt="Biblioteca">
    </div>
    <div class="carousel-item">
      <img src="imagenes/piso1.jpeg" class="d-block w-100" alt="Piso1">
    </div>
    <div class="carousel-item">
      <img src="imagenes/salondeactos.jpeg" class="d-block w-100" alt="Salón de actos">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </button>
</div>

<main class="container my-5">

  <!-- INSCRIPCIONES -->
  <div class="text-center p-4 bg-light border rounded shadow-sm mb-5">
    <h3 class="text-primary fw-bold mb-2">Inscripciones 2026</h3>
    <p class="text-muted mb-0" style="font-size: 0.95rem;">
      A partir del <strong>15/12</strong>, dirígete con tu 
      <strong>cédula</strong>, <strong>carnet de adolescente al día</strong>, 
      <strong>carnet de vacunas</strong> y <strong>$200</strong> para SIET.
    </p>
  </div>

  <!-- LO QUE OFRECEMOS + CARRERAS -->
  <div class="row mb-5">
    <div class="col-md-6 mb-4">
      <div class="p-3 border rounded shadow-sm bg-light">
        <h4 class="text-center mb-3">¿Qué ofrecemos?</h4>
        <ul class="list-unstyled">
          <li>🎓 Carreras técnicas con alta salida laboral.</li>
          <li>🧑‍🏫 Docentes capacitados y cercanos.</li>
          <li>🧪 Laboratorios modernos.</li>
          <li>💼 Inserción laboral y pasantías.</li>
          <li>🕐 Horarios flexibles: mañana, tarde y noche.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 border rounded shadow-sm bg-white table-responsive">
        <h4 class="text-center mb-3">Carreras Técnicas</h4>
        <table class="table table-bordered">
          <thead>
            <tr><th>Carrera</th><th>Duración</th><th>Turno</th></tr>
          </thead>
          <tbody>
            <tr><td>Bachillerato en Tecnológico Tecnologías de la Información Bilingüe</td><td>3 años</td><td>Matutino/Vespertino</td></tr>
            <tr><td>Bachillerato en Tecnológico Tecnologías de la Información</td><td>3 años</td><td>Matutino/Vespertino</td></tr>
            <tr><td>Curso técnico terciario de Diseño Gráfico</td><td>3 años</td><td>Vespertino</td></tr>
            <tr><td>Secretariado Bilingüe - Inglés</td><td>2 años</td><td>Nocturno</td></tr>
            <tr><td>Curso Técnico Terciario Diseño Gráfico en Comunicación Visual</td><td>2 años</td><td>Nocturno</td></tr>
            <tr><td>Tecnólogo en Ciberseguridad</td><td>3 años</td><td>Nocturno</td></tr>
            <tr><td>Curso Técnico Terciario Redes y Comunicaciones Ópticas</td><td>2 años</td><td>Nocturno</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- MAPA -->
  <div class="mb-5">
    <div class="p-3 border rounded shadow-sm bg-light">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3371.724859342374!2d-58.086378025346406!3d-32.31924484047528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95afcbfdd7fbb8eb%3A0xa8125d5f102fc8e4!2sITS%20Paysandu%20UTU!5e0!3m2!1ses!2suy!4v1753101421536!5m2!1ses!2suy"
        width="100%" height="300" style="border:0;" loading="lazy">
      </iframe>
    </div>
  </div>

  <!-- DOCENTES -->
  <div class="mb-5">
    <h2 class="text-center mb-4">Conoce a nuestros docentes</h2>
    <input id="buscar-docente" type="text" placeholder="Buscar docente..." class="form-control mb-4">

    <div class="docentes-grid">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="docente-card">
            <img class="docente-photo"
                 src="<?= !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'imagenes/LOGO.jpeg' ?>"
                 alt="Foto de <?= htmlspecialchars($row['nombrecompleto']) ?>" />
            <div class="docente-name"><?= htmlspecialchars($row['nombrecompleto']) ?></div>
            <div class="docente-subject"><?= htmlspecialchars($row['asignatura'] ?? 'Sin asignatura') ?></div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No hay docentes registrados aún.</p>
      <?php endif; ?>
    </div>

    <div class="ver-todo-container text-center mt-3">
      <a href="funcionarios.php" class="ver-todo-btn">Ver todo nuestro equipo</a>
    </div>
  </div>

  <!-- HISTORIA / MISIÓN / VISIÓN / VALORES -->
  <div class="info-grid mb-5">
    <div class="tarjeta">
      <h3>Historia</h3>
      <p>Fundado en 1975 para ofrecer educación técnica de calidad.</p>
    </div>
    <div class="tarjeta">
      <h3>Misión</h3>
      <p>Formar profesionales competentes y éticos fomentando innovación e investigación.</p>
    </div>
    <div class="tarjeta">
      <h3>Visión</h3>
      <p>Ser un instituto líder en educación tecnológica en Uruguay.</p>
    </div>
    <div class="tarjeta">
      <h3>Valores</h3>
      <ul>
        <li>Excelencia académica</li>
        <li>Compromiso social</li>
        <li>Innovación y tecnología</li>
        <li>Respeto y ética profesional</li>
        <li>Trabajo colaborativo</li>
      </ul>
    </div>
  </div>

  <!-- CONTACTO -->
  <div class="text-center mb-5">
    <h2 class="mb-4">Contacto</h2>
    <p><strong>Dirección:</strong> Sarandí entre Herrera y 19 de abril, Paysandú, Uruguay</p>
    <p><strong>Teléfono:</strong> +598 99676284</p>
    <p><strong>Email:</strong> evolutionit2008@gmail.com</p>
  </div>

</main>

<?php require("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="index.js"></script>

</body>
</html>

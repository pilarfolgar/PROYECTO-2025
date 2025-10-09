<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservas - InfraLex</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="styleindexdocente.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<!-- FORMULARIO RESERVA ESTILO DOCENTES -->
<div id="formularioReserva" class="formulario">
  <form id="formReservaAula">
    <button type="button" class="cerrar" onclick="cerrarFormularioAula()">&times;</button>
    <h2 class="form-title">Reservar Aula</h2>
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Grupo</label>
      <select class="form-select" id="grupoReserva" required>
        <option selected disabled>Seleccione grupo...</option>
        <option value="1MA">1°MA</option>
        <option value="2BB">2°BB</option>
        <option value="3CA">3°CA</option>
        <option value="4DA">4°DA</option>
        <option value="5EA">5°EA</option>
        <option value="6FA">6°FA</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Aula</label>
      <input type="text" class="form-control" id="aulaSeleccionadaAula" readonly required>
    </div>
    <div class="mb-3">
      <label>Fecha de reserva</label>
      <input type="date" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Hora de inicio</label>
      <input type="time" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Cantidad de horas</label>
      <select class="form-select" required>
        <option selected disabled>Elija...</option>
        <option value="1">1 hora</option>
        <option value="2">2 horas</option>
        <option value="3">3 horas</option>
        <option value="4">4 horas</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Comentarios</label>
      <textarea class="form-control"></textarea>
    </div>
    <button class="boton" type="submit">Reservar</button>
  </form>
</div>

<header>
    <div>
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
    <a href="index.php"><img src="imagenes/logopoyecto.png" alt="Logo" class="logo"></a>
</header>
<nav>
   
    <a href="funcionarios.php">Funcionarios</a>
    <a href="usuarios.html">Mi Perfil</a>
</nav>
<div class="container mt-4">
<div class="text-center mb-3">
  <button class="boton-filtro active" id="filtro-todo" onclick="filtrar('todo')">Todos</button>
  <button class="boton-filtro" id="filtro-aula" onclick="filtrar('aula')">Aulas</button>
  <button class="boton-filtro" id="filtro-salon" onclick="filtrar('salon')">Salones</button>
  <button class="boton-filtro" id="filtro-lab" onclick="filtrar('lab')">Laboratorios</button>
</div>

<?php
require("conexion.php");
$con = conectar_bd();
$sql = "SELECT id_aula, codigo, capacidad, ubicacion, imagen FROM aula ORDER BY codigo";
$result = $con->query($sql);
?>

<div class="row g-3">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="col-md-4 espacio aula">
        <div class="tarjeta">
            <img src="<?= $row['imagen'] ?: 'imagenes/default-aula.jpg' ?>" alt="<?= $row['codigo'] ?>" data-bs-toggle="modal" data-bs-target="#modalImagen" onclick="mostrarImagen(this)">
            <h4><?= $row['codigo'] ?></h4>
            <p>Capacidad: <?= $row['capacidad'] ?> personas<br>Ubicación: <?= $row['ubicacion'] ?></p>
            <button class="btn btn-primary" onclick="abrirReserva('<?= $row['codigo'] ?>')">Reservar</button>
        </div>
    </div>
<?php endwhile; ?>
</div>

<div class="modal fade" id="modalImagen" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="imagenAmpliada" src="" class="w-100" style="object-fit: contain;">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloReserva">Reservar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formReserva">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Aula</label>
            <input type="text" class="form-control" id="aulaSeleccionada" readonly required>
          </div>
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de inicio</label>
            <input type="time" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de fin</label>
            <input type="time" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select class="form-select" id="grupoReserva" required>
              <option selected disabled>Seleccione grupo...</option>
              <option value="1MA">1°MA</option>
              <option value="2BB">2°BB</option>
              <option value="3CA">3°CA</option>
              <option value="4DA">4°DA</option>
              <option value="5EA">5°EA</option>
              <option value="6FA">6°FA</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="appaulas.js"></script>
<script>
function abrirReserva(nombreEspacio) {
  document.getElementById('aulaSeleccionadaAula').value = nombreEspacio;
  document.getElementById('formularioReserva').style.display = 'flex';
}
function cerrarFormularioAula() {
  document.getElementById('formularioReserva').style.display = 'none';
}
document.getElementById('formReservaAula').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Reserva confirmada ✅');
  cerrarFormularioAula();
});
</script>
<script>
function abrirReserva(nombreEspacio) {
  document.getElementById('tituloReserva').innerText = "Reservar - " + nombreEspacio;
  document.getElementById('aulaSeleccionada').value = nombreEspacio;
  let modal = new bootstrap.Modal(document.getElementById('modalReserva'));
  modal.show();
}
</script>
<!-- La función filtrar ahora está en appaulas.js -->
</body>
</html>
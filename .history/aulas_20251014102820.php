<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservas - InfraLex</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<?php 
require("header.php"); 
require("conexion.php");
$con = conectar_bd();
?>

<div class="container my-4">
    <div class="text-center mb-3">
        <button class="btn btn-outline-primary boton-filtro active" id="filtro-todo" onclick="filtrar('todo')">Todos</button>
        <button class="btn btn-outline-primary boton-filtro" id="filtro-aula" onclick="filtrar('aula')">Aulas</button>
        <button class="btn btn-outline-primary boton-filtro" id="filtro-salon" onclick="filtrar('salon')">Salones</button>
        <button class="btn btn-outline-primary boton-filtro" id="filtro-lab" onclick="filtrar('lab')">Laboratorios</button>
    </div>

    <?php
    $sql = "SELECT id_aula, codigo, capacidad, ubicacion, imagen, tipo FROM aula ORDER BY codigo";
    $result = $con->query($sql);
    ?>

    <div class="row g-3">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 espacio <?= htmlspecialchars($row['tipo']) ?>">
            <div class="card h-100 shadow-sm">
                <img src="<?= $row['imagen'] ?: 'imagenes/default-aula.jpg' ?>" 
                     alt="<?= htmlspecialchars($row['codigo']) ?>" 
                     class="card-img-top"
                     onclick="mostrarImagen(this)">
                <div class="card-body text-center">
                    <h4 class="card-title"><?= htmlspecialchars($row['codigo']) ?></h4>
                    <p>Capacidad: <?= $row['capacidad'] ?> personas<br>Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></p>
                    <button class="btn btn-success w-100" onclick="abrirReserva('<?= htmlspecialchars($row['codigo']) ?>')">Reservar</button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal Imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="imagenAmpliada" src="" class="w-100" style="object-fit: contain;">
      </div>
    </div>
  </div>
</div>

<!-- Modal Reserva -->
<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloReserva">Reservar Aula</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formReserva">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Aula</label>
            <input type="text" name="aula" id="aulaSeleccionada" class="form-control" readonly required>
          </div>
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de inicio</label>
            <input type="time" name="hora_inicio" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora de fin</label>
            <input type="time" name="hora_fin" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select name="grupo" class="form-select" required>
              <option selected disabled>Seleccione grupo...</option>
              <?php
              // Trae los grupos de la base de datos
              $sql_grupos = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
              $result_grupos = $con->query($sql_grupos);
              while($grupo = $result_grupos->fetch_assoc()){
                  echo '<option value="'.htmlspecialchars($grupo['nombre']).'">'
                       .htmlspecialchars($grupo['nombre']).' - '.htmlspecialchars($grupo['orientacion'])
                       .'</option>';
              }
              ?>
            </select>
          </div>
          <div id="mensajeReserva" class="mb-3 text-center"></div>
          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="appaulas.js"></script>
</body>
</html>

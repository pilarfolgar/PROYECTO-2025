<?php
require("header.php");
require("conexion.php");
$con = conectar_bd();
?>

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

<div class="container my-4">
    <div class="text-center mb-3">
        <button class="btn btn-outline-primary boton-filtro active" onclick="filtrar('todo')">Todos</button>
        <button class="btn btn-outline-primary boton-filtro" onclick="filtrar('aula')">Aulas</button>
        <button class="btn btn-outline-primary boton-filtro" onclick="filtrar('salon')">Salones</button>
        <button class="btn btn-outline-primary boton-filtro" onclick="filtrar('lab')">Laboratorios</button>
    </div>

    <?php
    $sql_aulas = "SELECT id_aula, codigo, capacidad, ubicacion, imagen, tipo FROM aula ORDER BY codigo";
    $res_aulas = $con->query($sql_aulas);
    ?>

    <div class="row g-3">
        <?php while($aula = $res_aulas->fetch_assoc()): ?>
        <div class="col-md-4 espacio <?= htmlspecialchars($aula['tipo']) ?>">
            <div class="card h-100 shadow-sm">
                <img src="<?= $aula['imagen'] ?: 'imagenes/default-aula.jpg' ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($aula['codigo']) ?>"
                     onclick="mostrarImagen(this)">
                <div class="card-body text-center">
                    <h4 class="card-title"><?= htmlspecialchars($aula['codigo']) ?></h4>
                    <p>Capacidad: <?= $aula['capacidad'] ?> personas<br>Ubicación: <?= htmlspecialchars($aula['ubicacion']) ?></p>
                    <button class="btn btn-success w-100" onclick="abrirReserva(<?= $aula['id_aula'] ?>,'<?= htmlspecialchars($aula['codigo']) ?>')">Reservar</button>
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
          <input type="hidden" name="id_aula" id="idAulaSeleccionada">

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Aula</label>
            <input type="text" name="aula_nombre" id="aulaSeleccionada" class="form-control" readonly required>
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
            <select name="id_grupo" class="form-select" required>
              <option selected disabled>Seleccione grupo...</option>
              <?php
              $sql_grupos = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
              $res_grupos = $con->query($sql_grupos);
              while($g = $res_grupos->fetch_assoc()){
                  echo '<option value="'.intval($g['id_grupo']).'">'.htmlspecialchars($g['nombre']).' - '.htmlspecialchars($g['orientacion']).'</option>';
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
<script>
function abrirReserva(idAula, nombreAula){
    document.getElementById('tituloReserva').innerText = `Reservar - ${nombreAula}`;
    document.getElementById('idAulaSeleccionada').value = idAula;
    document.getElementById('aulaSeleccionada').value = nombreAula;
    new bootstrap.Modal(document.getElementById('modalReserva')).show();
}

function mostrarImagen(img){
    document.getElementById('imagenAmpliada').src = img.src;
}

function filtrar(categoria){
    document.querySelectorAll('.boton-filtro').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.espacio').forEach(el => {
        el.style.display = (categoria==='todo' || el.classList.contains(categoria))?'block':'none';
    });
    if(categoria!=='todo'){
        document.getElementById('filtro-'+categoria)?.classList.add('active');
    } else {
        document.getElementById('filtro-todo')?.classList.add('active');
    }
}

// Enviar reserva
document.getElementById('formReserva').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const mensaje = document.getElementById('mensajeReserva');

    fetch('guardar_reserva.php', {method:'POST', body: formData})
    .then(res => res.json())
    .then(data => {
        if(data.success){
            mensaje.innerHTML = `<span class="text-success">${data.message}</span>`;
            this.reset();
            setTimeout(()=>bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide(), 1500);
        } else {
            mensaje.innerHTML = `<span class="text-danger">${data.message}</span>`;
        }
    })
    .catch(err=>{
        console.error(err);
        mensaje.innerHTML = `<span class="text-danger">Error de conexión</span>`;
    });
});
</script>
</body>
</html>

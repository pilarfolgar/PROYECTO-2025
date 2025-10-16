<?php  
require("seguridad.php");
require("header.php"); 
require("conexion.php");
$con = conectar_bd();

$cedula_usuario = $_SESSION['cedula']; // ✅ viene de la sesión (usuario logueado)
$nombre_usuario = $_SESSION['nombrecompleto'];

// Función para generar bloques horarios
function generarBloques($horaInicio, $horaFin, $duracion = 45, $recreo = 5) {
    $bloques = [];
    $h = strtotime($horaInicio);
    $fin = strtotime($horaFin);
    while ($h + ($duracion*60) <= $fin) {
        $hora_inicio = date("H:i", $h);
        $h_fin = $h + ($duracion*60);
        $hora_fin = date("H:i", $h_fin);
        $bloques[] = ['inicio' => $hora_inicio, 'fin' => $hora_fin];
        $h += ($duracion + $recreo)*60;
    }
    return $bloques;
}

$bloques_horarios = generarBloques("07:00", "23:59");

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_aula'])) {
    $id_aula = intval($_POST['id_aula']);
    $aula_nombre = $con->real_escape_string($_POST['aula_nombre']);
    $fecha = $con->real_escape_string($_POST['fecha']);
    $hora_inicio = $con->real_escape_string($_POST['hora_inicio']);
    $hora_fin = $con->real_escape_string($_POST['hora_fin']);
    $id_grupo = intval($_POST['id_grupo']);

    // Verificar disponibilidad
    $sql_check = "SELECT * FROM reserva
                  WHERE id_aula=$id_aula AND fecha='$fecha'
                  AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
                  OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
                  OR (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin'))";
    $result = $con->query($sql_check);

    if($result->num_rows > 0){
        $mensaje = '<div class="alert alert-danger text-center">El aula ya está reservada en ese horario.</div>';
    } else {
        // ✅ Guardamos el número de cédula en el campo `nombre`
        $sql = "INSERT INTO reserva (id_aula, aula, nombre, fecha, hora_inicio, hora_fin, grupo)
                VALUES ($id_aula, '$aula_nombre', '$cedula_usuario', '$fecha', '$hora_inicio', '$hora_fin', $id_grupo)";
        if ($con->query($sql)) {
            $mensaje = '<div class="alert alert-success text-center">Reserva confirmada ✅</div>';
        } else {
            $mensaje = '<div class="alert alert-danger text-center">Error al guardar la reserva.</div>';
        }
    }
}
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
    <?php if($mensaje) echo $mensaje; ?>

    <div class="text-center mb-3">
        <button class="btn btn-outline-primary active" onclick="filtrar('todo')">Todos</button>
        <button class="btn btn-outline-primary" onclick="filtrar('aula')">Aulas</button>
        <button class="btn btn-outline-primary" onclick="filtrar('salon')">Salones</button>
        <button class="btn btn-outline-primary" onclick="filtrar('lab')">Laboratorios</button>
    </div>

    <div class="row g-3">
        <?php
        $sql = "SELECT id_aula, codigo, capacidad, ubicacion, imagen, tipo FROM aula ORDER BY codigo";
        $result = $con->query($sql);
        while($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 espacio <?= htmlspecialchars($row['tipo']) ?>">
            <div class="card h-100 shadow-sm">
                <img src="<?= $row['imagen'] ?: 'imagenes/default-aula.jpg' ?>" 
                     alt="<?= htmlspecialchars($row['codigo']) ?>" 
                     class="card-img-top">
                <div class="card-body text-center">
                    <h4 class="card-title"><?= htmlspecialchars($row['codigo']) ?></h4>
                    <p>Capacidad: <?= $row['capacidad'] ?> personas<br>Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></p>
                    <button class="btn btn-success w-100" 
                            onclick="abrirReserva(<?= $row['id_aula'] ?>, '<?= htmlspecialchars($row['codigo']) ?>')">
                        Reservar
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
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
        <form method="POST">
          <input type="hidden" name="id_aula" id="idAulaSeleccionada">
          <input type="hidden" name="aula_nombre" id="aulaSeleccionada">
          <!-- ✅ Cedula del docente logueado -->
          <input type="hidden" name="nombre" value="<?= htmlspecialchars($cedula_usuario) ?>">

          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Hora inicio</label>
            <select name="hora_inicio" class="form-select" required>
                <option selected disabled>Seleccione hora de inicio...</option>
                <?php foreach($bloques_horarios as $bloque): ?>
                    <option value="<?= $bloque['inicio'] ?>"><?= $bloque['inicio'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Hora fin</label>
            <select name="hora_fin" class="form-select" required>
                <option selected disabled>Seleccione hora de fin...</option>
                <?php foreach($bloques_horarios as $bloque): ?>
                    <option value="<?= $bloque['fin'] ?>"><?= $bloque['fin'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select name="id_grupo" class="form-select" required>
              <option selected disabled>Seleccione grupo...</option>
              <?php
              $sql_grupos = "SELECT id_grupo, nombre, orientacion FROM grupo ORDER BY nombre";
              $result_grupos = $con->query($sql_grupos);
              while($grupo = $result_grupos->fetch_assoc()){
                  echo '<option value="'.intval($grupo['id_grupo']).'">'.htmlspecialchars($grupo['nombre']).' - '.htmlspecialchars($grupo['orientacion']).'</option>';
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
function abrirReserva(idAula, nombreAula) {
    document.getElementById('tituloReserva').innerText = `Reservar - ${nombreAula}`;
    document.getElementById('idAulaSeleccionada').value = idAula;
    document.getElementById('aulaSeleccionada').value = nombreAula;
    const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
    modal.show();
}
function filtrar(tipo){
    document.querySelectorAll('.espacio').forEach(card=>{
        card.style.display = (tipo==='todo'||card.classList.contains(tipo)) ? 'block':'none';
    });
}
</script>
</body>
</html>

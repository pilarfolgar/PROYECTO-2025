<?php 
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Panel Docentes - InfraLex</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<?php require("header.php"); ?>

<!-- SECCIÓN MIS CURSOS -->
<section class="mis-cursos">
  <h2>Mis cursos</h2>
  <div class="docentes-grid">
    <div class="docente-card">
      <div class="docente-photo"></div>
      <div class="docente-name">1°MA - Lengua</div>
      <div class="docente-subject">Turno matutino</div>
      <button class="boton ver-miembros" data-clase="1°MA - Lengua">Ver miembros</button>
      <ul class="lista-miembros" style="display:none;"></ul>
    </div>
    <div class="docente-card">
      <div class="docente-photo"></div>
      <div class="docente-name">2°BB - Matemática</div>
      <div class="docente-subject">Turno vespertino</div>
      <button class="boton ver-miembros" data-clase="2°BB - Matemática">Ver miembros</button>
      <ul class="lista-miembros" style="display:none;"></ul>
    </div>
  </div>
</section>

<!-- SECCIÓN MIS RESERVAS -->
<section>
  <h2>Mis reservas</h2>
  <div id="reservas-container">
    <?php
    $docente_actual = $_SESSION['nombre'];
    $sql_reservas = "SELECT aula, fecha, hora_inicio, hora_fin, grupo
                     FROM reserva 
                     WHERE nombre = '$docente_actual'
                     ORDER BY fecha DESC, hora_inicio ASC";
    $result_reservas = $con->query($sql_reservas);

    if ($result_reservas && $result_reservas->num_rows > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped text-center">';
        echo '<thead class="table-primary"><tr>
                <th>Aula</th>
                <th>Grupo</th>
                <th>Fecha</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
              </tr></thead><tbody>';
        while ($row = $result_reservas->fetch_assoc()) {
            echo '<tr>
                    <td>'.htmlspecialchars($row['aula']).'</td>
                    <td>'.htmlspecialchars($row['grupo'] ?? 'Sin grupo').'</td>
                    <td>'.htmlspecialchars($row['fecha']).'</td>
                    <td>'.htmlspecialchars($row['hora_inicio']).'</td>
                    <td>'.htmlspecialchars($row['hora_fin']).'</td>
                  </tr>';
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="no-reservas text-center text-muted">No hay reservas por el momento.</div>';
    }
    ?>
  </div>
</section>

<!-- SECCIÓN VISTA PREVIA DE AULAS -->
<section class="container mt-5 mb-5 pt-4 pb-4 bg-light rounded-4 shadow-sm">
  <h2 class="text-center mb-4">Vista previa de Aulas</h2>
  <div class="row g-4 justify-content-center">
    <?php
    $sql_aulas = "SELECT codigo, capacidad, imagen FROM aula ORDER BY codigo LIMIT 5";
    $result_aulas = $con->query($sql_aulas);
    while ($row = $result_aulas->fetch_assoc()) {
        $img = $row['imagen'] ?: 'imagenes/default-aula.jpg';
        echo '
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <img src="Imagenes/'.$img.'" class="card-img-top" alt="'.$row["codigo"].'">
            <div class="card-body text-center">
              <h5 class="card-title">'.$row["codigo"].'</h5>
              <p class="card-text">Capacidad: '.$row["capacidad"].' personas</p>
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

<!-- SECCIÓN CALENDARIO DE RESERVAS -->
<main class="contenedor">
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

          $reservas = [];
          $fecha_actual = date('Y-m-d');
          foreach ($aulas as $aula) {
              $reservas[$aula] = [];
              $sql_r = "SELECT hora_inicio, hora_fin FROM reserva WHERE aula='$aula' AND fecha='$fecha_actual'";
              $res = $con->query($sql_r);
              while($row_r = $res->fetch_assoc()){
                  $reservas[$aula][] = ['hora_inicio'=>$row_r['hora_inicio'], 'hora_fin'=>$row_r['hora_fin']];
              }
          }

          function bloqueOcupado($hora_bloque, $reservas_aula){
              foreach($reservas_aula as $res){
                  if($hora_bloque >= $res['hora_inicio'] && $hora_bloque < $res['hora_fin']){
                      return true;
                  }
              }
              return false;
          }

          foreach ($horas as $hora) {
              echo '<tr>';
              echo '<td><strong>'.$hora.'</strong></td>';
              foreach ($aulas as $aula) {
                  if(bloqueOcupado($hora, $reservas[$aula])){
                      echo '<td class="bg-gradient bg-danger text-white"><span title="Reservado"><span class="badge rounded-pill bg-danger" style="font-size:1em;padding:0.6em 1.2em"><i class="bi bi-x-circle-fill"></i></span></td>';
                  } else {
                      echo '<td class="bg-gradient bg-success text-dark disponible" 
                               data-aula="'.$aula.'" data-hora="'.$hora.'" 
                               onclick="abrirReservaBloque(this)">
                               <span title="Disponible"><span class="badge rounded-pill bg-success" style="font-size:1em;padding:0.6em 1.2em"><i class="bi bi-check-circle-fill"></i></span>
                            </td>';
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
  </div>
</div>

<!-- FORMULARIO RESERVA MODAL -->
<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloReserva">Reservar Aula</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="guardar-reserva.php">
          <input type="hidden" name="aula_nombre" id="aulaSeleccionada">
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?= date('Y-m-d') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Hora inicio</label>
            <input type="time" name="hora_inicio" class="form-control" readonly required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hora fin</label>
            <input type="time" name="hora_fin" class="form-control" readonly required>
          </div>
          <button type="submit" class="btn btn-success w-100">Confirmar Reserva</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  &copy; 2025 Instituto Tecnológico Superior de Paysandú
</footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
function abrirReservaBloque(td){
    const aula = td.getAttribute('data-aula');
    const hora = td.getAttribute('data-hora');
    document.getElementById('tituloReserva').innerText = `Reservar - ${aula}`;
    document.getElementById('aulaSeleccionada').value = aula;
    document.querySelector('input[name="hora_inicio"]').value = hora;

    const [h,m] = hora.split(':').map(Number);
    let horaFinH = h;
    let horaFinM = m + 45;
    if(horaFinM >= 60){
        horaFinH += Math.floor(horaFinM/60);
        horaFinM = horaFinM % 60;
    }
    document.querySelector('input[name="hora_fin"]').value = `${horaFinH.toString().padStart(2,'0')}:${horaFinM.toString().padStart(2,'0')}`;

    const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
    modal.show();
}
</script>

</body>
</html>

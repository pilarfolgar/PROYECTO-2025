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
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
.docentes-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}
.docente-card {
    flex: 1 1 calc(25% - 1rem);
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
.docente-photo {
    width: 80px;
    height: 80px;
    background-color: #dee2e6;
    border-radius: 50%;
    margin: 0 auto 0.5rem auto;
}
.boton { margin-top: 0.5rem; }
.lista-miembros { list-style: none; padding-left: 0; }
@media (max-width: 768px) { .docente-card { flex: 1 1 calc(50% - 1rem); } }
@media (max-width: 576px) { .docente-card { flex: 1 1 100%; } }
</style>

</head>
<body>

<?php require("header.php"); ?>

<!-- SECCIÓN MIS CURSOS -->
<section class="mis-cursos container mt-4">
  <h2 class="mb-3">Mis cursos</h2>
  <div class="docentes-grid">
    <?php
    $docente_cedula = $_SESSION['cedula'];
    
   $stmt = $con->prepare("SELECT DISTINCT g.id_grupo, g.nombre, g.orientacion, g.cantidad_estudiantes
                       FROM grupo g
                       JOIN grupo_asignatura ga ON g.id_grupo = ga.id_grupo
                       JOIN docente_asignatura da ON ga.id_asignatura = da.id_asignatura
                       WHERE da.cedula_docente = ?
                       ORDER BY g.nombre");
$stmt->bind_param("s", $docente_cedula);
$stmt->execute();
$result_grupos = $stmt->get_result();


    if ($result_grupos && $result_grupos->num_rows > 0) {
        while ($row = $result_grupos->fetch_assoc()) {
            echo '<div class="docente-card">';
            echo '<div class="docente-photo"></div>';
            echo '<div class="docente-name fw-bold">'.htmlspecialchars($row['nombre']).'</div>';
            echo '<div class="docente-subject text-muted">'.htmlspecialchars($row['orientacion']).'</div>';
            echo '<button class="btn btn-outline-primary boton ver-miembros" data-clase="'.htmlspecialchars($row['nombre']).'">Ver miembros</button>';
            echo '<ul class="lista-miembros" style="display:none;">';
            
            // Cargar miembros del grupo
            $id_grupo = $row['id_grupo'];
            $sql_miembros = "SELECT nombre_estudiante FROM estudiante WHERE id_grupo = '$id_grupo' ORDER BY nombre_estudiante";
            $res_miembros = $con->query($sql_miembros);
            if($res_miembros && $res_miembros->num_rows > 0){
                while($miembro = $res_miembros->fetch_assoc()){
                    echo '<li>'.htmlspecialchars($miembro['nombre_estudiante']).'</li>';
                }
            } else {
                echo '<li>No hay miembros</li>';
            }

            echo '</ul></div>';
        }
    } else {
        echo '<div class="text-muted">No tienes cursos asignados.</div>';
    }
    ?>
  </div>
</section>

<!-- SECCIÓN MIS RESERVAS -->
<section class="container mt-5">
  <h2 class="mb-3">Mis reservas</h2>
  <div id="reservas-container">
    <?php
    $sql_reservas = "SELECT aula, fecha, hora_inicio, hora_fin, grupo
                     FROM reserva 
                     WHERE cedula = '$docente_cedula'
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
        echo '<div class="text-center text-muted">No hay reservas por el momento.</div>';
    }
    ?>
  </div>
</section>

<!-- SECCIÓN VISTA PREVIA DE AULAS -->
<section class="container mt-5 mb-5 pt-4 pb-4 bg-light rounded-4 shadow-sm">
  <h2 class="text-center mb-4">Vista previa de Aulas</h2>
  <div class="row g-4 justify-content-center">
    <?php
    $sql_aulas_preview = "SELECT codigo, capacidad, imagen FROM aula ORDER BY codigo LIMIT 5";
    $result_aulas_preview = $con->query($sql_aulas_preview);
    while ($row = $result_aulas_preview->fetch_assoc()) {
        $img = $row['imagen'] ?: 'default-aula.jpg';
        echo '<div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <img src="imagenes/'.$img.'" class="card-img-top" alt="'.$row["codigo"].'">
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
<main class="container mb-5">
  <h2 class="text-center mb-4">Calendario diario de aulas</h2>
  <div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
      <thead class="table-primary">
        <tr>
          <th>Hora</th>
          <?php
          $sql_aulas = "SELECT codigo FROM aula ORDER BY codigo";
          $result_aulas = $con->query($sql_aulas);
          $aulas = [];
          while($row=$result_aulas->fetch_assoc()){
              $aulas[] = $row['codigo'];
              echo '<th>'.htmlspecialchars($row['codigo']).'</th>';
          }
          ?>
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

        $fecha_actual = date('Y-m-d');
        $reservas = [];
        foreach($aulas as $aula){
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

        foreach($bloques as $hora){
            echo '<tr>';
            echo '<td><strong>'.$hora.'</strong></td>';
            foreach($aulas as $aula){
                if(bloqueOcupado($hora, $reservas[$aula])){
                    echo '<td class="bg-danger text-white"><i class="bi bi-x-circle-fill"></i></td>';
                } else {
                    echo '<td class="bg-success text-dark disponible" data-aula="'.htmlspecialchars($aula).'" data-hora="'.$hora.'" onclick="abrirReservaBloque(this)"><i class="bi bi-check-circle-fill"></i></td>';
                }
            }
            echo '</tr>';
        }
        ?>
      </tbody>
    </table>
    <div class="mt-3 text-start">
      <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Disponible</span>
      <span class="badge bg-danger ms-2"><i class="bi bi-x-circle-fill"></i> Reservado</span>
    </div>
  </div>
</main>

<!-- MODAL RESERVA -->
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
            <input type="date" name="fecha" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
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

<?php require("footer.php"); ?>

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

document.querySelectorAll('.ver-miembros').forEach(btn => {
    btn.addEventListener('click', () => {
        const ul = btn.nextElementSibling;
        ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
</body>
</html>

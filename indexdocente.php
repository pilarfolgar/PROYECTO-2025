<?php  
require("seguridad.php");
require("conexion.php");
$con = conectar_bd();

// ================================
//  obtenemos miembros mediante ajax
// ================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'miembros') {
    $id_grupo = intval($_GET['id_grupo'] ?? 0);

    $sql = "SELECT nombrecompleto
            FROM usuario
            WHERE rol='estudiante' AND id_grupo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();

    $miembros = [];
    while ($row = $result->fetch_assoc()) {
        $miembros[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($miembros);
    exit; //  Detiene el resto del HTML
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Panel Docentes - InfraLex</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="styleindexdocente.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



</head>
<body>

<?php require("header.php"); ?>

<!-- ========================= -->
<!-- SECCIÓN MIS CURSOS -->
<!-- ========================= -->
<section class="mis-cursos container mt-4">
  <h2 class="mb-3">Mis cursos</h2>
  <div class="docentes-grid">
    <?php
    $docente_cedula = $_SESSION['cedula'];

    $sql = "SELECT DISTINCT g.id_grupo, g.nombre AS grupo_nombre, g.orientacion, a.nombre AS asignatura_nombre
            FROM docente_asignatura da
            JOIN asignatura a ON da.id_asignatura = a.id_asignatura
            JOIN grupo_asignatura ga ON a.id_asignatura = ga.id_asignatura
            JOIN grupo g ON ga.id_grupo = g.id_grupo
            WHERE da.cedula_docente = ?
            ORDER BY g.nombre, a.nombre";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $docente_cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div class="docente-card">';
        echo '<div class="docente-photo"></div>';
        echo '<div class="docente-name fw-bold">'.htmlspecialchars($row['grupo_nombre'].' - '.$row['asignatura_nombre']).'</div>';
        echo '<div class="docente-subject text-muted">'.htmlspecialchars($row['orientacion']).'</div>';
        echo '<button class="btn btn-outline-primary boton ver-miembros" data-grupo="'.$row['id_grupo'].'">Ver miembros</button>';
        echo '<button class="btn btn-outline-success boton enviar-notificacion" data-grupo="'.$row['id_grupo'].'">Enviar Notificación</button>';
        echo '<ul class="lista-miembros" id="miembros-'.$row['id_grupo'].'"></ul>';
        echo '</div>';
    }
    $stmt->close();
    ?>
  </div>

  
</section>


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

<!-- ========================= -->
<!-- SECCIÓN VISTA PREVIA DE AULAS -->
<!-- ========================= -->
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

<!-- ========================= -->
<!-- SECCIÓN CALENDARIO DE RESERVAS -->
<!-- ========================= -->
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
          while($row = $result_aulas->fetch_assoc()){
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
      <span class="badge bg-danger ms-2"><i class="bi bi-x-circle-fill"></i> Ocupado</span>
    </div>
  </div>
</main>
<!-- Modal Enviar Notificación -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" aria-labelledby="modalNotificacionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="procesar-notificacion-docente.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalNotificacionLabel">Enviar Notificación</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_grupo" id="noti_id_grupo">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" name="titulo" id="titulo" required>
          </div>
          <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea class="form-control" name="mensaje" id="mensaje" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php require("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="docente.js"></script>
</body>
</html>

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
    <div class="docente-card">
      <div class="docente-photo"></div>
      <div class="docente-name fw-bold">1°MA - Lengua</div>
      <div class="docente-subject text-muted">Turno matutino</div>
      <button class="btn btn-outline-primary boton ver-miembros" data-clase="1°MA - Lengua">Ver miembros</button>
      <ul class="lista-miembros" style="display:none;"></ul>
    </div>
    <div class="docente-card">
      <div class="docente-photo"></div>
      <div class="docente-name fw-bold">2°BB - Matemática</div>
      <div class="docente-subject text-muted">Turno vespertino</div>
      <button class="btn btn-outline-primary boton ver-miembros" data-clase="2°BB - Matemática">Ver miembros</button>
      <ul class="lista-miembros" style="display:none;"></ul>
    </div>
  </div>
</section>

<!-- SECCIÓN MIS RESERVAS -->
<section class="container mt-5">
  <h2 class="mb-3">Mis reservas</h2>
  <div id="reservas-container">
    <?php
    $cedula_docente = $_SESSION['cedula'];
    $sql_reservas = "SELECT aula, fecha, hora_inicio, hora_fin, grupo
                     FROM reserva 
                     WHERE cedula = '$cedula_docente'
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
                if($hora_bloque >= $res['hora_inicio'] && $hora_blo

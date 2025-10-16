<!-- MODAL RESERVAS -->
<div class="modal fade" id="modalReservas" tabindex="-1" aria-labelledby="modalReservasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primario text-white">
        <h5 class="modal-title" id="modalReservasLabel">Reservas de Aulas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php
        $result = $con->query("SELECT aula, fecha, hora_inicio, hora_fin FROM reservas ORDER BY fecha DESC");
        if ($result->num_rows > 0):
          echo "<table class='table table-striped'>";
          echo "<thead><tr><th>Aula</th><th>Fecha</th><th>Hora Inicio</th><th>Hora Fin</th></tr></thead><tbody>";
          while ($row = $result->fetch_assoc()):
            echo "<tr><td>{$row['aula']}</td><td>{$row['fecha']}</td><td>{$row['hora_inicio']}</td><td>{$row['hora_fin']}</td></tr>";
          endwhile;
          echo "</tbody></table>";
        else:
          echo "<p>No hay reservas registradas.</p>";
        endif;
        ?>
      </div>
    </div>
  </div>
</div>

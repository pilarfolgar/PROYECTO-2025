<!-- MODAL SUGERENCIAS -->
<div class="modal fade" id="modalSugerencias" tabindex="-1" aria-labelledby="modalSugerenciasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primario text-white">
        <h5 class="modal-title" id="modalSugerenciasLabel">Sugerencias de Estudiantes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php
        $result = $con->query("SELECT estudiante, sugerencia, fecha FROM sugerencias ORDER BY fecha DESC");
        if ($result->num_rows > 0):
          echo "<ul class='list-group'>";
          while ($row = $result->fetch_assoc()):
            echo "<li class='list-group-item'><strong>{$row['estudiante']}</strong>: {$row['sugerencia']}<br><small>{$row['fecha']}</small></li>";
          endwhile;
          echo "</ul>";
        else:
          echo "<p>No hay sugerencias enviadas.</p>";
        endif;
        ?>
      </div>
    </div>
  </div>
</div>

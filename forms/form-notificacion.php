<!-- FORMULARIO NOTIFICACIÓN -->
<div id="form-notificacion" class="formulario oculto">
  <h2>Enviar Notificación</h2>
  <form action="procesar_notificacion.php" method="POST" class="form-grid">
    <div>
      <label>Grupo:</label>
      <select name="id_grupo" required>
        <?php
        $grupos = $con->query("SELECT id, nombre FROM grupos ORDER BY nombre");
        while ($fila = $grupos->fetch_assoc()) {
          echo "<option value='{$fila['id']}'>{$fila['nombre']}</option>";
        }
        ?>
      </select>
    </div>
    <div>
      <label>Título:</label>
      <input type="text" name="titulo" required>
    </div>
    <div>
      <label>Mensaje:</label>
      <textarea name="mensaje" rows="4" required></textarea>
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Enviar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

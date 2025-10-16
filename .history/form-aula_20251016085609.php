<!-- FORMULARIO AULA -->
<div id="form-aula" class="formulario oculto">
  <h2>Registrar Aula</h2>
  <form action="procesar_aula.php" method="POST" class="form-grid">
    <div>
      <label>Nombre del Aula:</label>
      <input type="text" name="nombre" required>
    </div>
    <div>
      <label>Capacidad:</label>
      <input type="number" name="capacidad" min="1" required>
    </div>
    <div>
      <label>Ubicación:</label>
      <input type="text" name="ubicacion">
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Guardar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

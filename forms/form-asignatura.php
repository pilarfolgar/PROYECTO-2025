<!-- FORMULARIO ASIGNATURA -->
<div id="form-asignatura" class="formulario oculto">
  <h2>Registrar Asignatura</h2>
  <form action="procesar_asignatura.php" method="POST" class="form-grid">
    <div>
      <label>Código:</label>
      <input type="text" name="codigo" required>
    </div>
    <div>
      <label>Nombre:</label>
      <input type="text" name="nombre" required>
    </div>
    <div>
      <label>Descripción:</label>
      <textarea name="descripcion" rows="3"></textarea>
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Guardar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

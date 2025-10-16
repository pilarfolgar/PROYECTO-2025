<!-- FORMULARIO GRUPO -->
<div id="form-grupo" class="formulario oculto">
  <h2>Registrar Grupo</h2>
  <form action="procesar_grupo.php" method="POST" class="form-grid">
    <div>
      <label>Nombre del Grupo:</label>
      <input type="text" name="nombre" required>
    </div>
    <div>
      <label>Año Lectivo:</label>
      <input type="text" name="anio" placeholder="2025" required>
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Guardar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

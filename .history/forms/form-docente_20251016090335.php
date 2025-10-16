<!-- FORMULARIO DOCENTE -->
<div id="form-docente" class="formulario oculto">
  <h2>Registrar Docente</h2>
  <form action="procesar_docente.php" method="POST" class="form-grid">
    <div>
      <label>Cédula:</label>
      <input type="number" name="cedula" required>
    </div>
    <div>
      <label>Nombre Completo:</label>
      <input type="text" name="nombre" required>
    </div>
    <div>
      <label>Correo Electrónico:</label>
      <input type="email" name="correo" required>
    </div>
    <div>
      <label>Teléfono:</label>
      <input type="text" name="telefono" required>
    </div>
    <div>
      <label>Contraseña:</label>
      <input type="password" name="clave" required>
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Guardar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

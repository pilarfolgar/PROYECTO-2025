<!-- FORMULARIO HORARIO -->
<div id="form-horario" class="formulario oculto">
  <h2>Registrar Horario</h2>
  <form action="procesar_horario.php" method="POST" class="form-grid">
    <div>
      <label>Asignatura:</label>
      <select name="id_asignatura" required>
        <?php
        $asignaturas = $con->query("SELECT id, nombre FROM asignaturas ORDER BY nombre");
        while ($fila = $asignaturas->fetch_assoc()) {
          echo "<option value='{$fila['id']}'>{$fila['nombre']}</option>";
        }
        ?>
      </select>
    </div>
    <div>
      <label>Docente:</label>
      <select name="id_docente" required>
        <?php
        $docentes = $con->query("SELECT id, nombre FROM docentes ORDER BY nombre");
        while ($fila = $docentes->fetch_assoc()) {
          echo "<option value='{$fila['id']}'>{$fila['nombre']}</option>";
        }
        ?>
      </select>
    </div>
    <div>
      <label>Día:</label>
      <select name="dia" required>
        <option>Lunes</option>
        <option>Martes</option>
        <option>Miércoles</option>
        <option>Jueves</option>
        <option>Viernes</option>
      </select>
    </div>
    <div>
      <label>Hora Inicio:</label>
      <input type="time" name="hora_inicio" required>
    </div>
    <div>
      <label>Hora Fin:</label>
      <input type="time" name="hora_fin" required>
    </div>
    <div class="botones-form">
      <button type="submit" class="boton">Guardar</button>
      <button type="button" class="boton-cancelar" onclick="cerrarForm()">Cancelar</button>
    </div>
  </form>
</div>

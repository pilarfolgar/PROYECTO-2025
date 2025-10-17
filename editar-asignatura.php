<?php
require("conexion.php");
$con = conectar_bd();

$id = intval($_GET['id']);
$sql = "SELECT * FROM asignatura WHERE id_asignatura = $id";
$result = $con->query($sql);
$asignatura = $result->fetch_assoc();

// Obtener docentes asignados
$sql_docentes = "SELECT cedula_docente FROM docente_asignatura WHERE id_asignatura = $id";
$result_docentes = $con->query($sql_docentes);
$docentes_asignados = [];
while($row = $result_docentes->fetch_assoc()) {
    $docentes_asignados[] = $row['cedula_docente'];
}
?>

<h2>Editar Asignatura</h2>
<form action="actualizar-asignatura.php" method="POST">
  <input type="hidden" name="id_asignatura" value="<?= $asignatura['id_asignatura'] ?>">
  <label>Nombre:</label>
  <input type="text" name="nombre" value="<?= htmlspecialchars($asignatura['nombre']) ?>" required>
  <label>Código:</label>
  <input type="text" name="codigo" value="<?= htmlspecialchars($asignatura['codigo']) ?>" required>
  <label>Docentes:</label>
  <select name="docentes[]" multiple required>
    <?php
    $sql = "SELECT cedula, nombrecompleto, apellido FROM usuario WHERE rol='docente'";
    $result = $con->query($sql);
    while($docente = $result->fetch_assoc()){
        $selected = in_array($docente['cedula'], $docentes_asignados) ? "selected" : "";
        echo "<option value='{$docente['cedula']}' $selected>Prof. {$docente['nombrecompleto']} {$docente['apellido']}</option>";
    }
    ?>
  </select>
  <button type="submit">Actualizar</button>
</form>

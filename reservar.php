<?php
// Capturar datos del formulario
$nombre = $_POST['nombre'];
$ci = $_POST['ci'];
$turno = $_POST['turno'];
$clase = $_POST['clase'];
$materia = $_POST['materia'];
$aula = $_POST['aula'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

// Guardar en archivo plano
$linea = "$nombre,$ci,$turno,$clase,$materia,$aula,$fecha,$hora\n";
file_put_contents("reservas.txt", $linea, FILE_APPEND);

// Redirigir con confirmación
echo "<script>
  alert('Reserva registrada correctamente');
  window.location.href='reservas.php?ci=$ci';
</script>";
?>
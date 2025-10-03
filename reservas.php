<?php
$ci = isset($_GET['ci']) ? $_GET['ci'] : null;
$reservas = [];

if(file_exists("reservas.txt")){
  $file = fopen("reservas.txt", "r");
  while(($line = fgets($file)) !== false){
    $data = explode(",", trim($line));
    if(count($data) == 8){
      $reservas[] = $data;
    }
  }
  fclose($file);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reservas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Reservas registradas</h2>
<table border="1" cellpadding="5">
  <tr>
    <th>Nombre</th>
    <th>CI</th>
    <th>Turno</th>
    <th>Clase</th>
    <th>Materia</th>
    <th>Aula</th>
    <th>Fecha</th>
    <th>Hora</th>
  </tr>
  <?php foreach($reservas as $r): ?>
    <?php if(!$ci || $r[1] == $ci): ?>
      <tr>
        <td><?= htmlspecialchars($r[0]) ?></td>
        <td><?= htmlspecialchars($r[1]) ?></td>
        <td><?= htmlspecialchars($r[2]) ?></td>
        <td><?= htmlspecialchars($r[3]) ?></td>
        <td><?= htmlspecialchars($r[4]) ?></td>
        <td><?= htmlspecialchars($r[5]) ?></td>
        <td><?= htmlspecialchars($r[6]) ?></td>
        <td><?= htmlspecialchars($r[7]) ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>
</body>
</html>
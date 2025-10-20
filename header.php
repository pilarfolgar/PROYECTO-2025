<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$inicio_link = 'index.php';
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'estudiante':
            $inicio_link = 'indexestudiante.php';
            break;
        case 'docente':
            $inicio_link = 'indexdocente.php';
            break;
        case 'administrativo':
            $inicio_link = 'indexadministrativo.php';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>InfraLex</title>

  <link rel="stylesheet" href="header.css">
  <script defer src="header.js"></script>
</head>
<body>

<header class="header">
  <div class="header-left">
    <img src="imagenes/logopoyecto.png" alt="Logo" class="logo">
    <div>
      <h1>InfraLex</h1>
      <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
  </div>
  <div class="header-right">
    <button id="menuBtn">☰</button>
  </div>
</header>


<nav class="nav">
  <a href="<?php echo $inicio_link; ?>">Inicio</a>
  <a href="#">Carreras</a>
</nav>


<div id="menu" class="menu">
  <a href="logout.php">Log Out</a>
  <a href="perfil.php">Mi Perfil</a>
</div>

</body>
</html>

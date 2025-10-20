<?php
session_start();

// Detectar rol y definir el enlace de inicio
$inicio_link = 'index.php'; // por defecto
if (isset($_SESSION['cedula']) && isset($_SESSION['rol'])) {
    switch($_SESSION['rol']) {
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

<!-- HEADER -->
<header style="display:flex;justify-content:space-between;align-items:center;background:#1B3A4B;color:white;padding:20px 30px;position:relative; height:90px;">
  <div style="display:flex;align-items:center;gap:20px;">
    <!-- Logo redondo -->
    <img src="imagenes/logopoyecto.png" alt="Logo" style="height:60px;width:60px;border-radius:50%;">
    <!-- Título -->
    <div>
      <h1 style="margin:0;font-size:32px;font-family:'Segoe UI',sans-serif;font-weight:bold;">InfraLex</h1>
      <h6 style="margin:0;font-size:16px;font-family:'Segoe UI',sans-serif;font-weight:normal;">Instituto Tecnológico Superior de Paysandú</h6>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:15px;position:relative;">
    <!-- Botón menú desplegable -->
    <button id="menuBtn" style="font-size:28px;background:none;border:none;color:white;cursor:pointer;">☰</button>
  </div>
</header>

<!-- NAV ABAJO -->
<nav style="display:flex;justify-content:center;gap:20px;background:#588BAE;padding:12px;border-top:2px solid #417899;">
  <a href="<?= $inicio_link ?>" style="color:white;text-decoration:none;padding:6px 12px;">Inicio</a>
  <a href="#" style="color:white;text-decoration:none;padding:6px 12px;">Carreras</a>
</nav>

<!-- MENÚ DESPLEGABLE -->
<div id="menu" style="display:none;position:absolute;top:90px;right:30px;background:#417899;padding:12px;border-radius:8px;flex-direction:column;">
  <a href="logout.php" style="color:white;text-decoration:none;padding:8px;display:block;">Log Out</a>
  <a href="perfil.php" style="color:white;text-decoration:none;padding:8px;display:block;">Mi Perfil</a>
</div>

<script>
  // Desplegar o cerrar menú
  const btnMenu = document.getElementById('menuBtn');
  const menu = document.getElementById('menu');
  btnMenu.onclick = () => menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
</script>

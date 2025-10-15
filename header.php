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
    <!-- Notificaciones neutras -->
    <button id="notifBtn" style="background:none;border:none;color:#CCCCCC;font-size:24px;cursor:pointer;position:relative;">
      🔔
      <span id="notifCount" style="position:absolute;top:-5px;right:-8px;background:red;color:white;font-size:12px;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;">3</span>
    </button>
    <!-- Botón menú desplegable -->
    <button id="menuBtn" style="font-size:28px;background:none;border:none;color:white;cursor:pointer;">☰</button>
  </div>
</header>

<!-- NAV ABAJO -->
<nav style="display:flex;justify-content:center;gap:20px;background:#588BAE;padding:12px;border-top:2px solid #417899;">
  <a href="index.php" style="color:white;text-decoration:none;padding:6px 12px;">Inicio</a>
  <a href="" style="color:white;text-decoration:none;padding:6px 12px;">Carreras</a>
</nav>

<!-- MENÚ DESPLEGABLE -->
<div id="menu" style="display:none;position:absolute;top:90px;right:30px;background:#417899;padding:12px;border-radius:8px;flex-direction:column;">
  <a href="logout.php" style="color:white;text-decoration:none;padding:8px;display:block;">Log Out</a>
  <a href="perfil.php" style="color:white;text-decoration:none;padding:8px;display:block;">Configuración</a>
</div>

<!-- MENÚ DE NOTIFICACIONES -->
<div id="notifMenu" style="display:none;position:absolute;top:90px;right:70px;background:#417899;padding:10px;border-radius:8px;flex-direction:column;min-width:200px;">
  <div style="color:white;padding:5px 8px;border-bottom:1px solid #588BAE;">Nueva reserva aprobada</div>
  <div style="color:white;padding:5px 8px;border-bottom:1px solid #588BAE;">Tarea pendiente</div>
  <div style="color:white;padding:5px 8px;">Mensaje del administrador</div>
</div>

<script>
  // Desplegar o cerrar menú
  const btnMenu = document.getElementById('menuBtn');
  const menu = document.getElementById('menu');
  btnMenu.onclick = () => menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';

  // Desplegar o cerrar notificaciones
  const btnNotif = document.getElementById('notifBtn');
  const notifMenu = document.getElementById('notifMenu');
  btnNotif.onclick = () => notifMenu.style.display = notifMenu.style.display === 'flex' ? 'none' : 'flex';
</script>

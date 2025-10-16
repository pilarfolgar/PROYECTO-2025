<?php
session_start();
require("conexion.php");
$con = conectar_bd();

$cedula_estudiante = $_SESSION['cedula'] ?? 0;

if(!$cedula_estudiante){
    echo "No se ha iniciado sesión.";
    exit();
}

// Obtenemos el grupo del estudiante
$sqlGrupo = "SELECT id_grupo FROM usuario WHERE cedula = ?";
$stmtG = $con->prepare($sqlGrupo);
$stmtG->bind_param("i", $cedula_estudiante);
$stmtG->execute();
$stmtG->bind_result($id_grupo);
$stmtG->fetch();
$stmtG->close();

// Marcar como leído
if(isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])){
    $id_notificacion = intval($_GET['marcar_visto']);
    $sqlVisto = "UPDATE notificaciones SET visto_estudiante = 1 WHERE id = ? AND id_grupo = ?";
    $stmtV = $con->prepare($sqlVisto);
    $stmtV->bind_param("ii", $id_notificacion, $id_grupo);
    $stmtV->execute();
    $stmtV->close();
}

// Traer notificaciones del grupo del estudiante
$sql = "SELECT id, titulo, mensaje, fecha, visto_estudiante
        FROM notificaciones
        WHERE id_grupo = ?
        ORDER BY fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_notificacion, $titulo, $mensaje, $fecha, $visto);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Notificaciones</title>
<style>
/* ---------------- VARIABLES ---------------- */
:root {
  --primario: #1B3A4B;
  --secundario: #588BAE;
  --acento: #A2D5F2;
  --fondo: #F0F4F8;
  --texto: #1B3A4B;
  --hover: #417899;
}

/* ---------------- GLOBAL ---------------- */
* { box-sizing: border-box; margin:0; padding:0; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; }
body { background-color: var(--fondo); color: var(--texto); transition: background 0.3s, color 0.3s; min-height: 100vh; }

/* ---------------- HEADER ---------------- */
header {
  background-color: var(--primario);
  color: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.header-left .logo {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background-color: white;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  transition: transform 0.3s ease;
}

.header-left .logo:hover { transform: scale(1.1); }

.header-left .header-text h2 { font-size: 1.8rem; margin-bottom: 0.2rem; }
.header-left .header-text h4 { font-size: 0.95rem; color: var(--acento); margin-top: 0; }

.header-right a {
  text-decoration: none;
  color: white;
  background-color: var(--acento);
  padding: 0.5rem 1rem;
  border-radius: 12px;
  font-weight: bold;
  transition: background 0.3s ease, transform 0.2s ease;
}

.header-right a:hover {
  background-color: var(--hover);
  color: white;
  transform: translateY(-2px);
}

/* ---------------- NAV ---------------- */
nav {
  background-color: var(--secundario);
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

nav a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  padding: 0.4rem 0.8rem;
  border-radius: 10px;
  transition: background 0.3s ease, transform 0.2s ease;
}

nav a:hover {
  background-color: var(--hover);
  transform: translateY(-2px);
}

/* ---------------- NOTIFICACIONES ---------------- */
h2 { text-align: center; margin: 2rem 0; color: var(--primario); font-size: 2rem; }

.notificaciones-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto 2rem;
}

.notificacion {
  border-radius: 12px;
  padding: 1.5rem 2rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
  position: relative;
  overflow: hidden;
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s forwards;
}

.notificacion:nth-child(1) { animation-delay: 0.1s; }
.notificacion:nth-child(2) { animation-delay: 0.2s; }
.notificacion:nth-child(3) { animation-delay: 0.3s; }
.notificacion:nth-child(4) { animation-delay: 0.4s; }

@keyframes fadeInUp {
  to { opacity: 1; transform: translateY(0); }
}

.notificacion:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.nuevo { background-color: #e8f4ff; border-left: 6px solid var(--secundario); }
.visto { background-color: #f4f4f4; border-left: 6px solid #ccc; }

.notificacion h3 { color: var(--primario); margin-bottom: 0.5rem; font-size: 1.3rem; }
.notificacion p { margin-bottom: 0.5rem; line-height: 1.5; }
.fecha { font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; }

.notificacion a {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  background-color: var(--acento);
  color: var(--primario);
  font-weight: bold;
  text-decoration: none;
  transition: background 0.3s, transform 0.2s;
}

.notificacion a:hover {
  background-color: var(--hover);
  color: white;
  transform: translateY(-2px);
}

.notificacion span {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  background-color: #ccc;
  color: #333;
  font-weight: bold;
}

/* ---------------- FOOTER ---------------- */
.footer {
  background-color: var(--primario);
  text-align: center;
  padding: 1rem;
  color: white;
  margin-top: 2rem;
}
.footer a { color: var(--acento); text-decoration: underline; }
.footer a:hover { color: white; text-decoration: none; }

/* ---------------- RESPONSIVE ---------------- */
@media (max-width: 768px) {
  body { padding: 1rem; }
  h2 { font-size: 1.5rem; }
  header { flex-direction: column; text-align: center; }
  .header-left { justify-content: center; }
  nav { flex-direction: column; gap: 0.5rem; }
}

@media (max-width: 480px) {
  h2 { font-size: 1.3rem; }
  .header-left { flex-direction: column; }
}
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="header-left">
    <div class="logo"></div>
    <div class="header-text">
      <h2>Mi Plataforma</h2>
      <h4>Bienvenido</h4>
    </div>
  </div>
  <div class="header-right">
    <a href="#">Perfil</a>
  </div>
</header>

<!-- NAV -->
<nav>
  <a href="#">Inicio</a>
  <a href="#">Cursos</a>
  <a href="#">Notificaciones</a>
  <a href="#">Contacto</a>
</nav>

<!-- NOTIFICACIONES -->
<h2>Mis Notificaciones</h2>
<div class="notificaciones-container">
<?php while($stmt->fetch()): ?>
    <div class="notificacion <?php echo $visto ? 'visto' : 'nuevo'; ?>">
        <h3><?php echo htmlspecialchars($titulo); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($mensaje)); ?></p>
        <p class="fecha"><?php echo $fecha; ?></p>
        <?php if(!$visto): ?>
            <a href="?marcar_visto=<?php echo $id_notificacion; ?>">Marcar como leído</a>
        <?php else: ?>
            <span>Leído</span>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>

<!-- FOOTER -->
<div class="footer">
  &copy; <?php echo date("Y"); ?> Mi Plataforma | <a href="#">Soporte</a>
</div>

<?php
$stmt->close();
$con->close();
?>
</body>
</html>

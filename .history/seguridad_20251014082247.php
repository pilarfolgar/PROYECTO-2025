<?php
// seguridad.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("nav_tokens.php");

// 1) Sesión mínima: user logueado
if (!isset($_SESSION['cedula']) || empty($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// 2) Validar token de navegación
// Aceptamos token via GET o POST (GET es más simple para links)
$token = null;
if (isset($_GET['t'])) $token = $_GET['t'];
elseif (isset($_POST['t'])) $token = $_POST['t'];

// limpiar tokens caducados antes de validar
limpiar_nav_tokens();

if (!$token || !validar_nav_token($token)) {
    // Opcional: podés guardar un mensaje en session para informar
    $_SESSION['mensaje'] = "Acceso directo no permitido. Por favor iniciá sesión nuevamente.";
    // Destruir sesión para forzar re-login si querés
    // session_unset(); session_destroy();
    header("Location: iniciosesion.php");
    exit();
}
?>

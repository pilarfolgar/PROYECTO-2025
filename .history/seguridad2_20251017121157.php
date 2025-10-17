<?php
session_start();

//Tiempo máximo de inactividad (en segundos)
$tiempo_sesion = 1800; // 30 minutos

//Verificar si hay usuario logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit;
}

//Control de expiración por tiempo
if (isset($_SESSION['tiempo_inicio'])) {
    $duracion = time() - $_SESSION['tiempo_inicio'];
    if ($duracion > $tiempo_sesion) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit;
    }
}
$_SESSION['tiempo_inicio'] = time();

//Generar token de página único
if (!isset($_SESSION['page_token'])) {
    $_SESSION['page_token'] = bin2hex(random_bytes(16));
    $_SESSION['page_url'] = $_SERVER['REQUEST_URI'];
} else {
    // Si el usuario refresca o cambia de pestaña, el token no coincide
    if ($_SESSION['page_url'] !== $_SERVER['REQUEST_URI']) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit;
    }

    // Si el token ya fue usado (recarga/F5), también cerrar sesión
    if (isset($_GET['token']) && $_GET['token'] !== $_SESSION['page_token']) {
        session_unset();
        session_destroy();
        header("Location: iniciosesion.php");
        exit;
    }
}

//Regenerar token cada carga para invalidar recargas o copia de URL
$token_nuevo = bin2hex(random_bytes(16));
$_SESSION['page_token'] = $token_nuevo;

//Reescribir la URL con el token nuevo
$url_actual = strtok($_SERVER["REQUEST_URI"], '?'); // sin parámetros
$query = $_GET;
$query['token'] = $token_nuevo;
$nueva_url = $url_actual . '?' . http_build_query($query);

// Redirige automáticamente con el nuevo token solo si no coincide
if (!isset($_GET['token']) || $_GET['token'] !== $token_nuevo) {
    header("Location: $nueva_url");
    exit;
}
?>


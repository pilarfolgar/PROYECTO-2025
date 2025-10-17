<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['cedula'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Verifica que haya hecho login correctamente
if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    // Aquí no destruimos la sesión, porque podría ser recarga
    // El control real se hace por token en sessionStorage
}
?>

<!-- JS para controlar token por pestaña -->
<script>
// Si no existe el token en esta pestaña → acceso directo → redirige a login
if (sessionStorage.getItem("token_pestana") !== "acceso") {
    alert("Debes iniciar sesión primero.");
    window.location.href = "iniciosesion.php";
}
</script>



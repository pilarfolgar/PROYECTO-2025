<?php
session_start();
session_unset();
session_destroy();

// Borrar cookie de token
setcookie("token_usuario", "", time() - 3600, "/");

header("Location: iniciosesion.php");
exit;

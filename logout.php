<?php
session_start();
session_destroy();
setcookie("token_usuario", "", time() - 3600, "/");
header("Location: iniciosesion.php");
exit();

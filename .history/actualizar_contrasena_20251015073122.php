<?php
require("conexion.php");
session_start();
$con = conectar_bd();

if (!isset($_SESSION['reset_email'])) {
    header("Location: recuperar.php");
    exit;
}

$email = $_SESSION['reset_email'];
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

if ($pass1 !== $pass2) {
    $_SESSION['mensaje'] = "Las contraseñas no coinciden.";
    header("Location: nueva_contrasena.php");
    exit;
}

$hash = password_hash($pass1, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($con, "UPDATE usuario SET pass=?, reset_token=NULL, reset_expira=NULL WHERE email=?");
mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
mysqli_stmt_execute($stmt);

unset($_SESSION['reset_email']);
$_SESSION['mensaje'] = "Contraseña actualizada con éxito. Podés iniciar sesión.";
header("Location: iniciosesion.php");
exit;
?>

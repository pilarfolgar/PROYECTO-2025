<?php
require("conexion.php");
$con = conectar_bd();

if (isset($_POST['token'], $_POST['pass'])) {
    $token = $_POST['token'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Siempre hashea la contraseña

    $query = "SELECT * FROM usuarios WHERE token='$token' AND token_expira > NOW()";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        mysqli_query($con, "UPDATE usuarios SET pass='$pass', token=NULL, token_expira=NULL WHERE token='$token'");
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Token inválido o expirado.";
    }
} else {
    header("Location: recuperar.php");
}

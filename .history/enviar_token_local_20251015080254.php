<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("conexion.php");
$con = conectar_bd();

session_start();
require("conexion.php");
$con = conectar_bd();

if(isset($_POST['email'])){
    $email = trim($_POST['email']);

    // Verificar si existe el usuario
    $stmt = mysqli_prepare($con, "SELECT cedula, nombrecompleto FROM usuario WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if($user = mysqli_fetch_assoc($res)) {
        // Generar token seguro
        $token = bin2hex(random_bytes(32)); // 64 caracteres
        $expira = date("Y-m-d H:i:s", time() + 900); // 15 minutos

        // Guardar en BD
        $stmt = mysqli_prepare($con, "UPDATE usuario SET reset_token=?, reset_expira=? WHERE email=?");
        mysqli_stmt_bind_param($stmt, "sss", $token, $expira, $email);
        mysqli_stmt_execute($stmt);

        // Link de recuperación
        $link = "http://localhost/PROYECTO-2025/nueva_contrasena.php?token=$token";

        echo "<div class='container my-5'>
                <h4>Hola {$user['nombrecompleto']}</h4>
                <p>Se generó un enlace de recuperación (válido 15 minutos):</p>
                <p><a href='$link'>$link</a></p>
                <p>Hacé clic para restablecer tu contraseña.</p>
              </div>";

    } else {
        echo "<div class='container my-5'>
                <p>No existe una cuenta con ese correo.</p>
              </div>";
    }
}
?>

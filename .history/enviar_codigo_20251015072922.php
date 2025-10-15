<?php
require("conexion.php");
session_start();
$con = conectar_bd();

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = mysqli_prepare($con, "SELECT cedula FROM usuario WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($res)) {
        $codigo = random_int(100000, 999999); // código de 6 dígitos
        $expira = date("Y-m-d H:i:s", time() + 900); // 15 min

        $stmt = mysqli_prepare($con, "UPDATE usuario SET reset_token=?, reset_expira=? WHERE email=?");
        mysqli_stmt_bind_param($stmt, "sss", $codigo, $expira, $email);
        mysqli_stmt_execute($stmt);

        // Enviar correo
        $asunto = "Código de recuperación de contraseña";
        $mensaje = "Tu código de recuperación es: $codigo\nExpira en 15 minutos.";
        $headers = "From: no-reply@tuweb.com\r\n";
        mail($email, $asunto, $mensaje, $headers);

        $_SESSION['mensaje'] = "Se ha enviado un código a tu correo.";
        header("Location: verificar_codigo.php?email=" . urlencode($email));
        exit;
    } else {
        $_SESSION['mensaje'] = "No existe una cuenta con ese correo.";
        header("Location: recuperar.php");
        exit;
    }
}
?>

<?php
require("conexion.php");
session_start();
$con = conectar_bd();

if (isset($_POST['email'], $_POST['codigo'])) {
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];

    $stmt = mysqli_prepare($con, "SELECT reset_token, reset_expira FROM usuario WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($res)) {
        if ($fila['reset_token'] == $codigo && strtotime($fila['reset_expira']) > time()) {
            // Código válido
            $_SESSION['reset_email'] = $email;
            header("Location: nueva_contrasena.php");
            exit;
        } else {
            $_SESSION['mensaje'] = "Código inválido o expirado.";
            header("Location: verificar_codigo.php?email=" . urlencode($email));
            exit;
        }
    }
}
?>

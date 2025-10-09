<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Verificar si existe el usuario
    $stmt = $con->prepare("SELECT cedula FROM usuario WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $con->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expira);
        $stmt->execute();

        $link = "http://localhost/PROYECTO-2025/restablecer.php?token=$token";
        $mensaje = "Link de recuperación generado: <a href='$link'>$link</a>";
    } else {
        $mensaje = "No existe una cuenta con ese email.";
    }
}
?>

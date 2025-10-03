<?php
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = mysqli_real_escape_string($con, $_POST["cedula"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE cedula='$cedula'";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if (password_verify($password, $usuario["pass"])) {
            session_start();
            $_SESSION["usuario"] = $usuario;
            header("Location: indexregistrado.php");
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>

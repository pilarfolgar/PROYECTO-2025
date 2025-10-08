<?php
session_start();
require("conexion.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre   = mysqli_real_escape_string($con, $_POST["nombre"]);
    $apellido = mysqli_real_escape_string($con, $_POST["apellido"]);
    $email    = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["pass"]);
    $cedula   = isset($_POST["cedula"]) ? intval($_POST["cedula"]) : 0;
    $rol      = isset($_POST["rol"]) ? mysqli_real_escape_string($con, $_POST["rol"]) : NULL;

    if (consultar_existe_usr($con, $cedula)) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        header("Location: registro.php");
        exit;
    } else {
        if (insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol)) {
            $_SESSION['msg_usuario'] = 'guardado';
            header("Location: registro.php");
            exit;
        } else {
            $_SESSION['error_usuario'] = 'error_general';
            header("Location: registro.php");
            exit;
        }
    }
}

function consultar_existe_usr($con, $cedula) {
    $consulta = "SELECT cedula FROM usuario WHERE cedula = '$cedula'";
    $resultado = mysqli_query($con, $consulta);
    return ($resultado && mysqli_num_rows($resultado) > 0);
}

function insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol) {
    $consulta_insertar = "INSERT INTO usuario
        (cedula, nombrecompleto, pass, apellido, email, rol)
        VALUES
        ('$cedula', '$nombre', '$password', '$apellido', '$email', '$rol')";
    return mysqli_query($con, $consulta_insertar);
}
?>

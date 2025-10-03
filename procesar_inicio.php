<?php
require("conexion.php");
$con = conectar_bd();

session_start();

if (isset($_POST["cedula"], $_POST["pass"])) {
    $cedula = $_POST["cedula"];
    $pass   = $_POST["pass"];
    logear($con, $cedula, $pass);
}

// función para traer datos
function traer_datos_usuario($con, $cedula) {
    $sql = "SELECT * FROM usuario WHERE cedula = '$cedula'";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_array($resultado);
        return [
            'cedula' => $row['cedula'],
            'nombre' => $row['nombrecompleto'],
            'email'  => $row['email'],
            'pass'   => $row['pass']
        ];
    } else {
        return null; // si no existe
    }
}

function logear($con, $cedula, $pass) {
    $datos_usr = traer_datos_usuario($con, $cedula);

    if ($datos_usr) {
        // verifico contraseña
        if ($pass == $datos_usr['pass']) {
            $_SESSION["cedula"]  = $cedula;
            $_SESSION["usuario"] = $datos_usr['nombre'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['mensaje'] = "<label style='color:red;'>Contraseña incorrecta</label>";
            header("Location: iniciosesion.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "<label style='color:red;'>Usuario no registrado</label>";
        header("Location: iniciosesion.php");
        exit();
    }
}
?>
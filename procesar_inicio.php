<?php
require("conexion.php");
require("validador_ci.php");

session_start();
$con = conectar_bd();

if (isset($_POST['cedula'], $_POST['pass'])) {

    $cedula_input = trim($_POST['cedula']);
    $pass = $_POST['pass'];

    if (empty($cedula_input) || empty($pass)) {
        $_SESSION['mensaje'] = 'Cédula y contraseña son obligatorios.';
        header("Location: iniciosesion.php");
        exit;
    }

    if (!preg_match('/^\d{8}$/', $cedula_input)) {
        $_SESSION['mensaje'] = 'La cédula debe tener exactamente 8 dígitos.';
        header("Location: iniciosesion.php");
        exit;
    }

    $ciValidator = new CI_Uruguay();
    if (!$ciValidator->validarCI($cedula_input)) {
        $_SESSION['mensaje'] = 'Cédula inválida.';
        header("Location: iniciosesion.php");
        exit;
    }

    $cedula = intval($cedula_input);

    $stmt = mysqli_prepare($con, "SELECT cedula, nombrecompleto, email, pass, rol FROM usuario WHERE cedula=?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {

        if (password_verify($pass, trim($fila['pass']))) {
            // Login exitoso
            $_SESSION['cedula'] = $fila['cedula'];
            $_SESSION['usuario'] = $fila['nombrecompleto'];
            $_SESSION['rol'] = $fila['rol'];
            $_SESSION['acceso_panel'] = true;

            // Token seguro y cookie persistente
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            setcookie("token_usuario", $token, time() + (30*24*60*60), "/", "", isset($_SERVER['HTTPS']), true);

            // Redirección según rol
            switch ($fila['rol']) {
                case 'estudiante':
                    header("Location: indexestudiante.php");
                    break;
                case 'docente':
                    header("Location: indexdocente.php");
                    break;
                default:
                    header("Location: indexadministrativo.php");
            }
            exit();
        } else {
            $_SESSION['mensaje'] = 'Contraseña incorrecta.';
            header("Location: iniciosesion.php");
            exit();
        }

    } else {
        $_SESSION['mensaje'] = 'Usuario no encontrado.';
        header("Location: iniciosesion.php");
        exit();
    }

} else {
    $_SESSION['mensaje'] = 'Debe enviar cédula y contraseña.';
    header("Location: iniciosesion.php");
    exit();
}

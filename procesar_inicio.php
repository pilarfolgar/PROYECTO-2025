<?php
require("conexion.php");
require("validador_ci.php");

session_start();
$con = conectar_bd();

if (isset($_POST['cedula'], $_POST['pass'])) {

    $cedula_input = trim($_POST['cedula']);
    $pass = $_POST['pass'];

    // Validaciones básicas
    if (empty($cedula_input) || empty($pass)) {
        $_SESSION['mensaje'] = 'Cédula y contraseña son obligatorios.';
        header("Location: iniciosesion.php");
        exit;
    }

    // Verificación formato cédula
    if (!preg_match('/^\d{8}$/', $cedula_input)) {
        $_SESSION['mensaje'] = 'La cédula debe tener exactamente 8 dígitos.';
        header("Location: iniciosesion.php");
        exit;
    }

    $ciValidator = new CI_Uruguay();
    if (!$ciValidator->validarCI($cedula_input)) {
        $_SESSION['mensaje'] = 'Cédula inválida (dígito verificador incorrecto).';
        header("Location: iniciosesion.php");
        exit;
    }

    $cedula = intval($cedula_input);

    // Traer usuario
    $stmt = mysqli_prepare($con, "SELECT cedula, nombrecompleto, email, pass, rol FROM usuario WHERE cedula=?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {

        $hash_bd = trim($fila['pass']); // trim por si hay espacios

        // Debug temporal
        error_log("DEBUG LOGIN - pass ingresada: '$pass'");
        error_log("DEBUG LOGIN - hash BD: '$hash_bd' | longitud: " . strlen($hash_bd));

        if (password_verify($pass, $hash_bd)) {
            // Login exitoso
            $_SESSION['cedula'] = $fila['cedula'];
            $_SESSION['usuario'] = $fila['nombrecompleto'];
            $_SESSION['rol'] = $fila['rol'];
            
    $_SESSION['acceso_panel'] = true;
     echo '<script>
                    sessionStorage.setItem("token_pestana", "acceso");';

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
            exit;
        }

    } else {
        $_SESSION['mensaje'] = 'Usuario no encontrado (cédula no registrada).';
        header("Location: iniciosesion.php");
        exit;
    }
}
?>

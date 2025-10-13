<?php
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();
session_start();  // Al inicio para sesiones

if (isset($_POST["cedula"], $_POST["pass"])) {
    $cedula_input = trim($_POST["cedula"] ?? '');  // Limpia input
    $pass = $_POST["pass"] ?? '';

    // Validaciones básicas
    if (empty($cedula_input) || empty($pass)) {
        $_SESSION['mensaje'] = 'Cédula y contraseña son obligatorios.';
        header("Location: iniciosesion.php");
        exit;
    }

    // 1️⃣ Validación de CI (bool directo, como en registro)
    if (!preg_match('/^\d{8}$/', $cedula_input)) {
        $_SESSION['mensaje'] = 'La cédula debe tener exactamente 8 dígitos.';
        header("Location: iniciosesion.php");
        exit;
    }

    $ciValidator = new CI_Uruguay();
    $validacionCI = $ciValidator->validarCI($cedula_input);  // Retorna bool
    if (!$validacionCI) {
        $_SESSION['mensaje'] = 'Cédula inválida (dígito verificador incorrecto).';
        header("Location: iniciosesion.php");
        exit;
    }

    $cedula = intval($cedula_input);  // Convierte a int después de validar
    logear($con, $cedula, $pass);
}

// Función para traer datos (con prepared statement para seguridad)
function traer_datos_usuario($con, $cedula) {
    $stmt = mysqli_prepare($con, "SELECT * FROM usuario WHERE cedula = ?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_array($resultado);
        return [
            'cedula' => $row['cedula'],
            'nombre' => $row['nombrecompleto'],
            'email'  => $row['email'],
            'pass'   => $row['pass'],
            'rol'    => $row['rol']
        ];
    } else {
        return null; // Si no existe
    }
}

function logear($con, $cedula, $pass) {
    $datos_usr = traer_datos_usuario($con, $cedula);

    if ($datos_usr) {
        error_log("DEBUG LOGIN - Cédula: $cedula | Pass ingresada: '$pass' | Hash en BD: '" . $datos_usr['pass'] . "' | Longitud hash: " . strlen($datos_usr['pass']));

        $hash_bd = $datos_usr['pass'];

        // Detecta si la contraseña no está hasheada (ej: texto plano)
        if (strlen($hash_bd) < 20 || !preg_match('/^\$2[ayb]\$/', $hash_bd)) {
            // Asumimos que es texto plano y lo convertimos a hash
            $nuevo_hash = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($con, "UPDATE usuario SET pass=? WHERE cedula=?");
            mysqli_stmt_bind_param($stmt, "si", $nuevo_hash, $cedula);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $hash_bd = $nuevo_hash;  // Actualizamos la variable para la verificación
            error_log("DEBUG LOGIN - Contraseña en texto plano convertida a hash para cédula $cedula");
        }

        // Verifico contraseña con hash
        if (password_verify($pass, $hash_bd)) {
            error_log("DEBUG LOGIN - password_verify: ÉXITO para cédula $cedula");
            $_SESSION["cedula"]  = $cedula;
            $_SESSION["usuario"] = $datos_usr['nombre'];
            $_SESSION["rol"]     = $datos_usr['rol'];

            // Redirección por rol
            $rol = $datos_usr['rol'];
            if ($rol === 'estudiante') {
                header("Location: indexestudiantes.php");
            } elseif ($rol === 'docente') {
                header("Location: indexdocentes.php");
            } else {
                header("Location: indexadministrativo.php");
            }
            exit();
        } else {
            error_log("DEBUG LOGIN - password_verify: FALLÓ para cédula $cedula (hash no coincide)");
            $_SESSION['mensaje'] = 'Contraseña incorrecta.';
            header("Location: iniciosesion.php");
            exit();
        }
    } else {
        error_log("DEBUG LOGIN - Usuario no encontrado para cédula $cedula");
        $_SESSION['mensaje'] = 'Usuario no encontrado (cédula no registrada).';
        header("Location: iniciosesion.php");
        exit();
    }
}
?>

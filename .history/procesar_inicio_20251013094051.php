<?php
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();
session_start();  // Mueve al inicio para sesiones

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
            'rol'    => $row['rol']  // Agrego rol para redirección
        ];
    } else {
        return null; // Si no existe
    }
}

function logear($con, $cedula, $pass) {
    $datos_usr = traer_datos_usuario($con, $cedula);

    if ($datos_usr) {
        // Verifico contraseña con hash (como en registro)
        if (password_verify($pass, $datos_usr['pass'])) {
            $_SESSION["cedula"]  = $cedula;
            $_SESSION["usuario"] = $datos_usr['nombre'];
            $_SESSION["rol"]     = $datos_usr['rol'];  // Guarda rol en sesión

            // Redirección por rol (ajusta URLs si es necesario)
            $rol = $datos_usr['rol'];
            if ($rol === 'estudiante') {
                header("Location: indexestudiantes.php");
            } elseif ($rol === 'docente') {
                // Para docentes: Si quieres bloquear "pendientes", agrega chequeo aquí
                // Ej: if (chequear_pendiente($con, $cedula)) { error; } else { ... }
                header("Location: indexdocentes.php");  // Ajusta a tu página de docentes
            } else {
                // Admin u otros: indexadministrativo
                header("Location: indexadministrativo.php");
            }
            exit();
        } else {
            $_SESSION['mensaje'] = 'Contraseña incorrecta.';
            header("Location: iniciosesion.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = 'Usuario no encontrado (cédula no registrada).';
        header("Location: iniciosesion.php");
        exit();
    }
}
?>
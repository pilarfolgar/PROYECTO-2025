<?php
session_start();
require("conexion.php");
require("validador_ci.php"); // archivo con la clase CI_Uruguay
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre   = mysqli_real_escape_string($con, $_POST["nombre"]);
    $apellido = mysqli_real_escape_string($con, $_POST["apellido"]);
    $email    = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["pass"]);
    $cedula   = isset($_POST["cedula"]) ? trim($_POST["cedula"]) : '';
    $rol      = isset($_POST["rol"]) ? mysqli_real_escape_string($con, $_POST["rol"]) : NULL;

    // -----------------------------
    // 1️⃣ Validación de CI (cédula)
    // -----------------------------
    $ciValidator = new CI_Uruguay();
    $validacionCI = $ciValidator->validarCI($cedula);

    if (!$validacionCI) {
        $_SESSION['error_usuario'] = 'ci_invalida';
        header("Location: registro.php");
        exit;
    }

    // -----------------------------
    // 2️⃣ Validación de rol
    // -----------------------------
    if ($rol !== 'estudiante' && $rol !== 'docente') {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    // -----------------------------
    // 3️⃣ Verificar si el usuario ya existe
    // -----------------------------
    if (consultar_existe_usr($con, $cedula)) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        header("Location: registro.php");
        exit;
    } else {

        // Si es docente, su estado será 'pendiente' (requiere aprobación)
        $estado = ($rol === 'docente') ? 'pendiente' : 'activo';

        // -----------------------------
        // 4️⃣ Insertar datos en la BD
        // -----------------------------
        if (insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol, $estado)) {

            if ($rol === 'estudiante') {
                $_SESSION['msg_usuario'] = 'guardado';
                // Estudiantes entran directamente
                header("Location: indexestudiantes.php");
            } elseif ($rol === 'docente') {
                // Docentes quedan en espera de verificación
                $_SESSION['msg_usuario'] = 'pendiente_verificacion';
                header("Location: registro.php");
            }
            exit;

        } else {
            $_SESSION['error_usuario'] = 'error_general';
            header("Location: registro.php");
            exit;
        }
    }
}

// -----------------------------
// Funciones auxiliares
// -----------------------------
function consultar_existe_usr($con, $cedula) {
    $consulta = "SELECT cedula FROM usuario WHERE cedula = '$cedula'";
    $resultado = mysqli_query($con, $consulta);
    return ($resultado && mysqli_num_rows($resultado) > 0);
}

function insertar_datos($con, $nombre, $apellido, $email, $password, $cedula, $rol, $estado) {
    $consulta_insertar = "INSERT INTO usuario
        (cedula, nombrecompleto, pass, apellido, email, rol, estado)
        VALUES
        ('$cedula', '$nombre', '$password', '$apellido', '$email', '$rol', '$estado')";
    return mysqli_query($con, $consulta_insertar);
}
?>


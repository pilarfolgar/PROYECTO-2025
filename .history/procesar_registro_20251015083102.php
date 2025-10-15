<?php
session_start();
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();

$secretKey = "6LfHIusrAAAAAJV9s4pN0LI7aceKeahhvZqJRS3w";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Validar CAPTCHA
    if (empty($_POST['g-recaptcha-response'])) {
        $_SESSION['error_usuario'] = 'captcha_faltante';
        header("Location: registro.php");
        exit;
    }

    $captchaResponse = $_POST['g-recaptcha-response'];
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    // Verificar CAPTCHA con Google
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse&remoteip=$remoteIp";
    $response = file_get_contents($verifyUrl);
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        $_SESSION['error_usuario'] = 'captcha_invalido';
        header("Location: registro.php");
        exit;
    }

    // ✅ Recibir datos
    $nombre   = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $email    = trim(strtolower($_POST["email"] ?? ''));
    $password = $_POST["pass"] ?? '';
    $cedula   = trim($_POST["cedula"] ?? '');
    $rol      = trim($_POST["rol"] ?? '');
    $clase    = trim($_POST["grupo"] ?? ''); // CORRECCIÓN: 'grupo' en el formulario

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_usuario'] = 'campos_vacios';
        header("Location: registro.php");
        exit;
    }

    // Hash de contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Validar cédula
    if (!preg_match('/^\d{8}$/', $cedula)) {
        $_SESSION['error_usuario'] = 'ci_invalida';
        header("Location: registro.php");
        exit;
    }

    $ciValidator = new CI_Uruguay();
    if (!$ciValidator->validarCI($cedula)) {
        $_SESSION['error_usuario'] = 'ci_invalida';
        header("Location: registro.php");
        exit;
    }
    $cedula_int = intval($cedula);

    // ✅ Validar rol
    if ($rol !== 'estudiante' && $rol !== 'docente') {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    // ✅ Validar grupo si es estudiante
    if ($rol === 'estudiante' && empty($clase)) {
        $_SESSION['error_usuario'] = 'clase_requerida';
        header("Location: registro.php");
        exit;
    }

    // ✅ Verificar duplicados por cédula
    if (consultar_existe_usr($con, $cedula_int)) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        header("Location: registro.php");
        exit;
    }

    // ✅ Verificar duplicados por email
    $stmt_email = mysqli_prepare($con, "SELECT email FROM usuario WHERE email = ?");
    mysqli_stmt_bind_param($stmt_email, "s", $email);
    mysqli_stmt_execute($stmt_email);
    mysqli_stmt_store_result($stmt_email);
    if (mysqli_stmt_num_rows($stmt_email) > 0) {
        $_SESSION['error_usuario'] = 'email_existente';
        mysqli_stmt_close($stmt_email);
        header("Location: registro.php");
        exit;
    }
    mysqli_stmt_close($stmt_email);

    // Preparar datos para inserción
    $nombrecompleto = $nombre . ' ' . $apellido;
    $telefono = '';
    $foto = NULL;
    $asignatura = ($rol === 'docente') ? '' : NULL;
    $id_grupo = ($rol === 'estudiante') ? $clase : NULL;

    // ✅ Insertar en base de datos
    if (insertar_datos($con, $cedula_int, $nombrecompleto, $password_hash, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo)) {
        if ($rol === 'estudiante') {
            $_SESSION['msg_usuario'] = 'guardado';
            header("Location: indexestudiante.php");
        } else {
            $_SESSION['msg_usuario'] = 'pendiente_verificacion';
            header("Location: registro.php");
        }
        exit;
    } else {
        $_SESSION['error_usuario'] = 'error_general';
        error_log("Error INSERT: " . mysqli_error($con) . " | Datos: cedula={$cedula_int}, email={$email}, rol={$rol}, grupo={$clase}");
        header("Location: registro.php");
        exit;
    }
}

/* ==========================
   FUNCIONES AUXILIARES
========================== */

function consultar_existe_usr($con, $cedula) {
    if ($cedula === NULL || $cedula === 0) return false;
    $stmt = mysqli_prepare($con, "SELECT cedula FROM usuario WHERE cedula = ?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = (mysqli_stmt_num_rows($stmt) > 0);
    mysqli_stmt_close($stmt);
    return $exists;
}

function insertar_datos($con, $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo) {
    $stmt = mysqli_prepare($con, "INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return false;

    // Manejar NULL para id_grupo y asignatura
    if ($id_grupo === NULL) {
        mysqli_stmt_bind_param($stmt, "isssssssss", $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo);
    } else {
        mysqli_stmt_bind_param($stmt, "isssssssss", $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo);
    }

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}
?>

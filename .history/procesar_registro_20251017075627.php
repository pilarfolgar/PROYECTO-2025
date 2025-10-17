<?php
session_start();
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();
$secretKey = "6LfHIusrAAAAAJV9s4pN0LI7aceKeahhvZqJRS3w";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ============================
    // 1️⃣ Validar CAPTCHA
    // ============================
    if (empty($_POST['g-recaptcha-response'])) {
        $_SESSION['error_usuario'] = 'captcha_faltante';
        header("Location: registro.php");
        exit;
    }

    $captchaResponse = $_POST['g-recaptcha-response'];
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse&remoteip=$remoteIp";
    $response = file_get_contents($verifyUrl);
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        $_SESSION['error_usuario'] = 'captcha_invalido';
        header("Location: registro.php");
        exit;
    }

    // ============================
    // 2️⃣ Recibir y sanitizar datos
    // ============================
    $nombre   = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $email    = trim(strtolower($_POST["email"] ?? ''));
    $password = $_POST["pass"] ?? '';
    $cedula   = trim($_POST["cedula"] ?? '');
    $rol      = trim($_POST["rol"] ?? '');
    $grupo    = trim($_POST["grupo"] ?? ''); // nombre del select en el form

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_usuario'] = 'campos_vacios';
        header("Location: registro.php");
        exit;
    }

    // ============================
    // 3️⃣ Validar cédula
    // ============================
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

    // ============================
    // 4️⃣ Validar rol y grupo
    // ============================
    if ($rol !== 'estudiante' && $rol !== 'docente') {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    if ($rol === 'estudiante' && empty($grupo)) {
        $_SESSION['error_usuario'] = 'clase_requerida';
        header("Location: registro.php");
        exit;
    }

    // ============================
    // 5️⃣ Verificar duplicados
    // ============================
    // Por cédula
    $stmt = mysqli_prepare($con, "SELECT cedula FROM usuario WHERE cedula = ?");
    mysqli_stmt_bind_param($stmt, "i", $cedula_int);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        mysqli_stmt_close($stmt);
        header("Location: registro.php");
        exit;
    }
    mysqli_stmt_close($stmt);

    // Por email
    $stmt = mysqli_prepare($con, "SELECT email FROM usuario WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error_usuario'] = 'email_existente';
        mysqli_stmt_close($stmt);
        header("Location: registro.php");
        exit;
    }
    mysqli_stmt_close($stmt);

    // ============================
    // 6️⃣ Preparar datos para insertar
    // ============================
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $nombrecompleto = $nombre . ' ' . $apellido;
    $telefono = '';
    $foto = NULL;
    $asignatura = ($rol === 'docente') ? '' : NULL;
    $id_grupo = ($rol === 'estudiante') ? intval($grupo) : NULL;

    // ============================
    // 7️⃣ Insertar en la base de datos
    // ============================
    $stmt = mysqli_prepare($con, "INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("Error preparar INSERT: " . mysqli_error($con));
        $_SESSION['error_usuario'] = 'error_general';
        header("Location: registro.php");
        exit;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "issssssssi",
        $cedula_int,
        $nombrecompleto,
        $password_hash,
        $apellido,
        $email,
        $rol,
        $telefono,
        $foto,
        $asignatura,
        $id_grupo
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        if ($rol === 'estudiante') {
            $_SESSION['msg_usuario'] = 'guardado';
            header("Location: indexestudiante.php");
        } else {
            $_SESSION['msg_usuario'] = 'pendiente_verificacion';
            header("Location: registro.php");
        }
        exit;
    } else {
        error_log("Error INSERT usuario: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        $_SESSION['error_usuario'] = 'error_general';
        header("Location: registro.php");
        exit;
    }
}
?>

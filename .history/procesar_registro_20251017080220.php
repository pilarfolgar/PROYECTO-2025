<?php
session_start();
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Depuración de errores MySQLi

$secretKey = "6LfHIusrAAAAAJV9s4pN0LI7aceKeahhvZqJRS3w";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // =========================
    // 1️⃣ Validar CAPTCHA
    // =========================
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

    // =========================
    // 2️⃣ Recibir y sanitizar datos
    // =========================
    $nombre    = trim($_POST["nombre"] ?? '');
    $apellido  = trim($_POST["apellido"] ?? '');
    $email     = strtolower(trim($_POST["email"] ?? ''));
    $password  = $_POST["pass"] ?? '';
    $cedula    = trim($_POST["cedula"] ?? '');
    $rol       = trim($_POST["rol"] ?? '');
    $id_grupo  = ($_POST["grupo"] ?? null); // Puede ser NULL

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_usuario'] = 'campos_vacios';
        header("Location: registro.php");
        exit;
    }

    // Validar rol
    if (!in_array($rol, ['estudiante', 'docente'])) {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    // Validar grupo si es estudiante
    if ($rol === 'estudiante' && empty($id_grupo)) {
        $_SESSION['error_usuario'] = 'clase_requerida';
        header("Location: registro.php");
        exit;
    }

    // =========================
    // 3️⃣ Validar cédula
    // =========================
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

    // =========================
    // 4️⃣ Verificar duplicados
    // =========================
    $stmt = $con->prepare("SELECT cedula FROM usuario WHERE cedula = ?");
    $stmt->bind_param("i", $cedula_int);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        $stmt->close();
        header("Location: registro.php");
        exit;
    }
    $stmt->close();

    $stmt = $con->prepare("SELECT email FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_usuario'] = 'email_existente';
        $stmt->close();
        header("Location: registro.php");
        exit;
    }
    $stmt->close();

    // =========================
    // 5️⃣ Preparar datos para insertar
    // =========================
    $password_hash  = password_hash($password, PASSWORD_DEFAULT);
    $nombrecompleto = $nombre . ' ' . $apellido;
    $telefono       = '';
    $foto           = NULL;
    $asignatura     = ($rol === 'docente') ? '' : NULL;
    $id_grupo       = ($rol === 'estudiante') ? intval($id_grupo) : NULL;

    // =========================
    // 6️⃣ Insertar en la base de datos
    // =========================
    $stmt = $con->prepare("INSERT INTO usuario 
        (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Manejar NULL correctamente
    $stmt->bind_param(
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

    if ($stmt->execute()) {
        $_SESSION['msg_usuario'] = ($rol === 'estudiante') ? 'guardado' : 'pendiente_verificacion';
        $redirect = ($rol === 'estudiante') ? "indexestudiante.php" : "registro.php";
        header("Location: $redirect");
        exit;
    } else {
        error_log("Error INSERT usuario: " . $stmt->error);
        $_SESSION['error_usuario'] = 'error_general';
        header("Location: registro.php");
        exit;
    }
}
?>

<?php
session_start();
require("conexion.php");
require("validador_ci.php");

$con = conectar_bd();
$secretKey = "6LfHIusrAAAAAJV9s4pN0LI7aceKeahhvZqJRS3w";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

// =====================
// 1. Validar CAPTCHA
// =====================
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

// =====================
// 2. Recibir datos
// =====================
$nombre     = trim($_POST["nombre"] ?? '');
$apellido   = trim($_POST["apellido"] ?? '');
$email      = trim(strtolower($_POST["email"] ?? ''));
$password   = $_POST["pass"] ?? '';
$cedula     = trim($_POST["cedula"] ?? '');
$rol        = trim($_POST["rol"] ?? '');
$grupo      = trim($_POST["grupo"] ?? ''); // solo estudiantes

// Validar campos obligatorios
if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
    $_SESSION['error_usuario'] = 'campos_vacios';
    header("Location: registro.php");
    exit;
}

// =====================
// 3. Validar cédula
// =====================
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

// =====================
// 4. Validar rol y grupo
// =====================
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

// =====================
// 5. Verificar duplicados
// =====================
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

// =====================
// 6. Preparar datos
// =====================
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$nombrecompleto = $nombre . ' ' . $apellido;
$telefono = '';
$foto = NULL;

if ($rol === 'estudiante') {
    $asignatura = NULL;
    $id_grupo = intval($grupo);
} else {
    $asignatura = '';
    $id_grupo = NULL;
}

// =====================
// 7. Insertar usuario
// =====================
$stmt = $con->prepare("INSERT INTO usuario 
    (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    error_log("Error preparar INSERT: " . mysqli_error($con));
    $_SESSION['error_usuario'] = 'error_general';
    header("Location: registro.php");
    exit;
}

// Bind correcto, NULL funciona
$stmt->bind_param(
    "isssssssss",
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
    $stmt->close();
    header("Location: " . (($rol === 'estudiante') ? "indexestudiante.php" : "registro.php"));
    exit;
} else {
    error_log("Error INSERT usuario: " . mysqli_stmt_error($stmt));
    $_SESSION['error_usuario'] = 'error_general';
    $stmt->close();
    header("Location: registro.php");
    exit;
}
?>

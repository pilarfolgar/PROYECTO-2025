<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// =========================
// Validar que venga por POST
// =========================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: indexadministrativo.php");
    exit();
}

// =========================
// Capturar datos del formulario
// =========================
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$documento = trim($_POST["documento"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");

// =========================
// Validar campos obligatorios
// =========================
if (empty($nombre) || empty($apellido) || empty($documento) || empty($email)) {
    $_SESSION['error_docente'] = "campos_vacios";
    header("Location: indexadministrativo.php");
    exit();
}

// =========================
// Verificar si ya existe docente con esa cédula
// =========================
$check = $con->prepare("SELECT cedula FROM usuario WHERE cedula = ?");
$check->bind_param("s", $documento);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['error_docente'] = "docente_existente";
    header("Location: indexadministrativo.php");
    exit();
}
$check->close();

// =========================
// Verificar si ya existe docente con ese email
// =========================
$check_email = $con->prepare("SELECT email FROM usuario WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    $_SESSION['error_docente'] = "email_existente";
    header("Location: indexadministrativo.php");
    exit();
}
$check_email->close();

// =========================
// Procesar imagen (foto)
// =========================
$foto = null;
if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $dir = "uploads/docentes/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid("docente_") . "." . strtolower($extension);
    $rutaDestino = $dir . $nombreArchivo;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
        $foto = $rutaDestino;
    }
}

// =========================
// Generar contraseña por defecto (cédula)
// =========================
$password_hash = password_hash($documento, PASSWORD_DEFAULT);

// =========================
// Insertar docente en la BD
// =========================
$sql = "INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto)
        VALUES (?, ?, ?, ?, ?, 'docente', ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssssss", $documento, $nombre, $password_hash, $apellido, $email, $telefono, $foto);

if ($stmt->execute()) {
    $_SESSION['msg_docente'] = "guardado";
} else {
    $_SESSION['error_docente'] = "error_bd";
}

// =========================
// Cerrar conexión y redirigir
// =========================
$stmt->close();
$con->close();

header("Location: indexadministrativo.php");
exit();
?>

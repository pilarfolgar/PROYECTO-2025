<?php
session_start();
require("conexion.php");
require("validador_ci.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = password_hash($_POST["pass"] ?? '', PASSWORD_DEFAULT);  // ¡Hashea!
    $cedula   = trim($_POST["cedula"] ?? '');
    $rol      = trim($_POST["rol"] ?? '');

    // Validaciones básicas (evita vacíos)
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_usuario'] = 'campos_vacios';
        header("Location: registro.php");
        exit;
    }

    // 1️⃣ Validación de CI (solo si no vacía; ajusta si es obligatoria)
    if (!empty($cedula)) {
        if (!preg_match('/^\d{8}$/', $cedula)) {
            $_SESSION['error_usuario'] = 'ci_invalida';
            header("Location: registro.php");
            exit;
        }
        $ciValidator = new CI_Uruguay();
        $validacionCI = $ciValidator->validarCI($cedula);
        if (!$validacionCI) {  // Nota: tu método retorna bool, no array
            $_SESSION['error_usuario'] = 'ci_invalida';
            header("Location: registro.php");
            exit;
        }
        $cedula = intval($cedula);  // Ahora sí, después de validar
    } else {
        $cedula = NULL;  // O 0 si DB requiere int; ajusta tabla
    }

    // 2️⃣ Validación de rol
    if ($rol !== 'estudiante' && $rol !== 'docente') {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    // 3️⃣ Verificar duplicados (cédula Y email)
    if (!empty($cedula) && consultar_existe_usr($con, $cedula)) {
        $_SESSION['error_usuario'] = 'usuario_existente';
        header("Location: registro.php");
        exit;
    }
    $check_email = mysqli_query($con, "SELECT email FROM usuario WHERE email = '" . mysqli_real_escape_string($con, $email) . "'");
    if ($check_email && mysqli_num_rows($check_email) > 0) {
        $_SESSION['error_usuario'] = 'email_existente';
        header("Location: registro.php");
        exit;
    }

    // 4️⃣ Insertar (estado por rol)
    $estado = ($rol === 'docente') ? 'pendiente' : 'activo';
    $nombrecompleto = $nombre . ' ' . $apellido;  // Corrige: une nombre y apellido

    if (insertar_datos($con, $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $estado)) {
        if ($rol === 'estudiante') {
            $_SESSION['msg_usuario'] = 'guardado';
            header("Location: indexestudiantes.php");
        } else {
            $_SESSION['msg_usuario'] = 'pendiente_verificacion';
            header("Location: registro.php");
        }
        exit;
    } else {
        $_SESSION['error_usuario'] = 'error_general';
        error_log("Error INSERT: " . mysqli_error($con));  // Log para debug
        header("Location: registro.php");
        exit;
    }
}

// Funciones (mejoradas con prepared statements para seguridad)
function consultar_existe_usr($con, $cedula) {
    if (empty($cedula)) return false;
    $stmt = mysqli_prepare($con, "SELECT cedula FROM usuario WHERE cedula = ?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return ($result && mysqli_num_rows($result) > 0);
}

function insertar_datos($con, $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $estado) {
    $stmt = mysqli_prepare($con, "INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssissss", $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $estado);
    return mysqli_stmt_execute($stmt);
}
?>
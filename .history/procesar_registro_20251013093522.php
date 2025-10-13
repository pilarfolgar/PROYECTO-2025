<?php
session_start();
require("conexion.php");
require("validador_ci.php");
$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $email    = trim(strtolower($_POST["email"] ?? ''));  // Minúsculas para consistencia
    $password = password_hash($_POST["pass"] ?? '', PASSWORD_DEFAULT);
    $cedula   = trim($_POST["cedula"] ?? '');
    $rol      = trim($_POST["rol"] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_usuario'] = 'campos_vacios';
        header("Location: registro.php");
        exit;
    }

    // 1️⃣ Validación de CI (obligatoria: 8 dígitos uruguayos)
    $cedula_int = NULL;
    if (!empty($cedula)) {
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
    } else {
        // Cédula obligatoria: fuerza error si vacía
        $_SESSION['error_usuario'] = 'ci_invalida';
        header("Location: registro.php");
        exit;
    }

    // 2️⃣ Validación de rol
    if ($rol !== 'estudiante' && $rol !== 'docente') {
        $_SESSION['error_usuario'] = 'rol_invalido';
        header("Location: registro.php");
        exit;
    }

    // 3️⃣ Verificar duplicados (cédula Y email)
    if (consultar_existe_usr($con, $cedula_int)) {
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

    // 4️⃣ Preparar valores para INSERT (sin campo 'estado'; usa campos existentes)
    $nombrecompleto = $nombre . ' ' . $apellido;
    $telefono = '';  // Vacío; usuario lo completa después
    $foto = NULL;    // Sin foto inicial
    $asignatura = ($rol === 'docente') ? '' : NULL;  // Docentes pueden agregar después
    $id_grupo = ($rol === 'estudiante') ? NULL : NULL;  // Grupos para estudiantes después

    if (insertar_datos($con, $cedula_int, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo)) {
        // Registro exitoso: maneja redirección por rol (sin 'estado' en BD)
        if ($rol === 'estudiante') {
            $_SESSION['msg_usuario'] = 'guardado';
            header("Location: indexestudiantes.php");
        } else {  // Docente: muestra mensaje de verificación pendiente (lógica en login futura)
            $_SESSION['msg_usuario'] = 'pendiente_verificacion';
            header("Location: registro.php");
        }
        exit;
    } else {
        $_SESSION['error_usuario'] = 'error_general';
        error_log("Error INSERT: " . mysqli_error($con) . " | Query details: cedula={$cedula_int}, email={$email}, rol={$rol}");  // Log detallado para debug
        header("Location: registro.php");
        exit;
    }
}

// Funciones (sin 'estado'; solo campos existentes)
function consultar_existe_usr($con, $cedula) {
    if ($cedula === NULL || $cedula === 0) return false;
    $stmt = mysqli_prepare($con, "SELECT cedula FROM usuario WHERE cedula = ?");
    mysqli_stmt_bind_param($stmt, "i", $cedula);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return ($result && mysqli_num_rows($result) > 0);
}

function insertar_datos($con, $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo) {
    // INSERT solo con campos existentes en la BD (sin 'estado')
    $stmt = mysqli_prepare($con, "INSERT INTO usuario (cedula, nombrecompleto, pass, apellido, email, rol, telefono, foto, asignatura, id_grupo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // Bind: i (int cedula), s (strings y NULLs)
    mysqli_stmt_bind_param($stmt, "isssssssss", $cedula, $nombrecompleto, $password, $apellido, $email, $rol, $telefono, $foto, $asignatura, $id_grupo);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}
?>
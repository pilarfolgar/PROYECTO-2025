<?php
require("conexion.php");
$con = conectar_bd();

$mensaje = "";
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $pass = trim($_POST['pass']);
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

    // Verificar token y expiración
    $stmt = $con->prepare("SELECT email FROM password_resets WHERE token=? AND expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $email = $row['email'];

        // Actualizar contraseña en usuario
        $stmt2 = $con->prepare("UPDATE usuario SET pass=? WHERE email=?");
        $stmt2->bind_param("ss", $pass_hash, $email);
        $stmt2->execute();

        // Eliminar token
        $stmt3 = $con->prepare("DELETE FROM password_resets WHERE token=?");
        $stmt3->bind_param("s", $token);
        $stmt3->execute();

        $mensaje = "✅ Contraseña restablecida correctamente. <a href='inicio_Sesion.php'>Iniciar sesión</a>";
    } else {
        $mensaje = "Token inválido o expirado.";
    }
}
?>

<!-- HTML similar a tu actual restablecer.php -->


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<main class="container my-5">
    <h2 class="text-center mb-4">Restablecer contraseña</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info text-center"><?= $mensaje ?></div>
    <?php endif; ?>

    <?php if($token && !$mensaje): ?>
        <form method="POST" class="p-4 border rounded bg-light mx-auto" style="max-width:400px;">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="mb-3">
                <label for="pass" class="form-label">Nueva contraseña</label>
                <input type="password" name="pass" id="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Restablecer contraseña</button>
        </form>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

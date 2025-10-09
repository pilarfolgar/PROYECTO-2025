<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";

$token = $_GET['token'] ?? '';

if ($token) {
    // Verificar token válido y no expirado
    $stmt = $con->prepare("SELECT email, expira FROM password_resets WHERE token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        if (strtotime($fila['expira']) > time()) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $email = $fila['email'];

                // Actualizar contraseña
                $stmt = $con->prepare("UPDATE usuario SET password=? WHERE email=?");
                $stmt->bind_param("ss", $password, $email);
                $stmt->execute();

                // Eliminar token usado
                $stmt = $con->prepare("DELETE FROM password_resets WHERE email=?");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                $mensaje = "✅ Contraseña restablecida correctamente.";
            }
        } else {
            $mensaje = "❌ El enlace ha expirado.";
        }
    } else {
        $mensaje = "❌ Token inválido.";
    }
} else {
    $mensaje = "❌ No se recibió token.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<main class="container my-5">
    <h2 class="text-center mb-4">Restablecer contraseña</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info text-center"><?= $mensaje ?></div>
    <?php endif; ?>

    <?php if($token && $res->num_rows > 0 && strtotime($fila['expira']) > time()): ?>
        <form method="POST" class="p-4 border rounded bg-light mx-auto" style="max-width:400px;">
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>

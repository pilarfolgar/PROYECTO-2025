<?php
require("conexion.php");
$con = conectar_bd();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Verificar si existe el email
    $stmt = $con->prepare("SELECT id FROM usuario WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar token en la tabla password_resets
        $stmt = $con->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expira);
        $stmt->execute();

        // Mostrar el link directamente en pantalla
        $link = "https://dbitsp.tailff9876.ts.net/evolutionit/PROYECTO-2025/restablecer.php?token=$token";
        $mensaje = "✅ Link para restablecer la contraseña: <a href='$link'>$link</a>";
    } else {
        $mensaje = "❌ No existe una cuenta con ese email.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<main class="container my-5">
    <h2 class="text-center mb-4">Recuperar contraseña</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info text-center"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" class="p-4 border rounded bg-light mx-auto" style="max-width:400px;">
        <div class="mb-3">
            <label for="email" class="form-label">Ingrese su email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Generar link</button>
    </form>
</main>
</body>
</html>

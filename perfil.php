<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['email'])) {
    header("Location: iniciosesion.php");
    exit;
}

$email = $_SESSION['email'];

// Traer datos del usuario desde la BD
$stmt = $con->prepare("SELECT nombrecompleto, email, rol FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - InfraLex</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
<?php require("header.php"); ?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Mi Perfil</h2>

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($user['nombrecompleto']) ?></h4>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
            <a href="editar_perfil.php" class="btn btn-primary w-100">Editar Perfil</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

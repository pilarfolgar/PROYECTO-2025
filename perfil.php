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

// Traer todos los datos del usuario desde la BD
$stmt = $con->prepare("SELECT cedula, nombrecompleto, apellido, email, rol, telefono, foto, asignatura, id_grupo FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Si no se encontró el usuario (algo raro)
if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<style>
    .perfil-card img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 15px;
    }
</style>
</head>
<body>
<?php require("header.php"); ?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Mi Perfil</h2>

    <div class="card perfil-card mx-auto text-center p-4 shadow-sm" style="max-width: 450px;">
        <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil">
        <h4 class="card-title"><?= htmlspecialchars($user['nombrecompleto'] . ' ' . $user['apellido']) ?></h4>
        <p><strong>Cédula:</strong> <?= htmlspecialchars($user['cedula']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['telefono'] ?: 'No registrado') ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
        <?php if($user['rol'] === 'docente'): ?>
            <p><strong>Asignatura:</strong> <?= htmlspecialchars($user['asignatura'] ?: 'No asignada') ?></p>
        <?php else: ?>
            <p><strong>Grupo:</strong> <?= htmlspecialchars($user['id_grupo'] ?: 'No asignado') ?></p>
        <?php endif; ?>
        <a href="editar_perfil.php" class="btn btn-primary w-100 mt-3">Editar Perfil</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

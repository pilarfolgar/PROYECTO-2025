<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión
if (!isset($_SESSION['cedula'])) {
    header("Location: index.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// Traer datos del usuario
$stmt = $con->prepare("SELECT cedula, nombrecompleto, apellido, email, rol, telefono, foto, asignatura, id_grupo FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) { 
    echo "Usuario no encontrado."; 
    exit; 
}

$rol = strtolower($user['rol']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="perfil.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php require("header.php"); ?>

<div class="perfil-container">
    <div class="perfil-card text-center">
        <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil" class="rounded-circle" width="150" height="150">
        <h4 class="mt-3"><?= htmlspecialchars($user['nombrecompleto']) ?></h4>

        <p><strong>Cédula:</strong> <?= htmlspecialchars($user['cedula']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['telefono'] ?: 'No registrado') ?></p>
        <p><strong>Rol:</strong> <?= ucfirst($rol) ?></p>

        <?php if($rol === 'docente'): ?>
            <p><strong>Asignatura:</strong> <?= htmlspecialchars($user['asignatura'] ?: 'No asignada') ?></p>
        <?php else: ?>
            <p><strong>Grupo:</strong> <?= htmlspecialchars($user['id_grupo'] ?: 'No asignado') ?></p>
        <?php endif; ?>

        <div class="perfil-actions mt-4">
            <!-- Enlace con el rol incluido -->
            <a href="editar_perfil.php?rol=<?= urlencode($rol) ?>" class="btn btn-primary">Editar Perfil</a>
            <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>

            <!-- Botón para eliminar cuenta -->
            <form id="eliminarCuentaForm" method="post" action="procesar_eliminado.php" class="mt-2">
                <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar Cuenta</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
// SweetAlert2 para confirmar eliminación de cuenta
document.getElementById('btnEliminar').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('eliminarCuentaForm').submit();
        }
    });
});
</script>

<?php include('footer.php'); ?>
</body>
</html>

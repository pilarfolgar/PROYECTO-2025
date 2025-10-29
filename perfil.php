<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión usando la cédula
if (!isset($_SESSION['cedula'])) {
    header("Location: index.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// Traer datos del usuario por cédula
$stmt = $con->prepare("SELECT cedula, nombrecompleto, apellido, email, rol, telefono, foto, asignatura, id_grupo FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) { echo "Usuario no encontrado."; exit; }
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="perfil.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php require("header.php"); ?>

<div class="perfil-container">
    <div class="perfil-card">
        <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil">
        <h4><?= htmlspecialchars($user['nombrecompleto'] . ' ' . $user['apellido']) ?></h4>
        <p><strong>Cédula:</strong> <?= htmlspecialchars($user['cedula']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['telefono'] ?: 'No registrado') ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
        <?php if($user['rol'] === 'docente'): ?>
            <p><strong>Asignatura:</strong> <?= htmlspecialchars($user['asignatura'] ?: 'No asignada') ?></p>
        <?php else: ?>
            <p><strong>Grupo:</strong> <?= htmlspecialchars($user['id_grupo'] ?: 'No asignado') ?></p>
        <?php endif; ?>

        <div class="perfil-actions">
            <a href="editar_perfil.php" class="btn-perfil btn-edit">Editar Perfil</a>
            <a href="logout.php" class="btn-perfil btn-logout">Cerrar Sesión</a>

            <!-- Botón para eliminar cuenta -->
            <form id="eliminarCuentaForm" method="post" action="procesar_eliminado.php">
                <button type="button" class="btn-perfil btn-delete mt-2" id="btnEliminar">Eliminar Cuenta</button>
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

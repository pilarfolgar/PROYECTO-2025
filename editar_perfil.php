<?php
session_start();
require("conexion.php");
$con = conectar_bd();

// Verificar sesión
if (!isset($_SESSION['cedula'])) {
    header("Location: login.php");
    exit;
}

$cedula = $_SESSION['cedula'];

// Traer datos del usuario
$stmt = $con->prepare("SELECT * FROM usuario WHERE cedula=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) { echo "Usuario no encontrado."; exit; }

$mensaje = '';

// Obtener grupos disponibles
$grupos_result = $con->query("SELECT id_grupo, nombre FROM grupo ORDER BY nombre");
$grupos = [];
while ($row = $grupos_result->fetch_assoc()) {
    $grupos[$row['id_grupo']] = $row['nombre'];
}

// Procesar edición de perfil...
// (Aquí va tu código de procesamiento de foto, contraseña y datos)
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Perfil - InfraLex</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="perfil.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.perfil-card {
    max-width: 600px;
    margin: 40px auto;
    padding: 2rem;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.perfil-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 20px;
    border: 4px solid #588BAE; /* Igual que perfil.php */
}

.perfil-card .form-control, .perfil-card .form-select, .perfil-card textarea {
    margin-bottom: 1rem;
}
</style>
</head>
<body>

<?php require("header.php"); ?>

<div class="container">
    <div class="perfil-card">
        <h2 class="mb-4">Editar Perfil</h2>

        <?php if(!empty($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <img src="<?= htmlspecialchars($user['foto'] ?: 'imagenes/default-user.png') ?>" alt="Foto de perfil">

        <form method="post" enctype="multipart/form-data" class="text-start mt-4">
            <div class="mb-3">
                <label class="form-label">Foto de Perfil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombrecompleto" class="form-control" value="<?= htmlspecialchars($user['nombrecompleto']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($user['apellido']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($user['telefono']) ?>">
            </div>

            <?php if($user['rol'] === 'docente'): ?>
                <div class="mb-3">
                    <label class="form-label">Asignatura</label>
                    <input type="text" name="asignatura" class="form-control" value="<?= htmlspecialchars($user['asignatura']) ?>">
                </div>
            <?php else: ?>
                <div class="mb-3">
                    <label class="form-label">Grupo</label>
                    <select name="id_grupo" class="form-control">
                        <option value="">-- Ningún grupo --</option>
                        <?php foreach($grupos as $id => $nombre_grupo): ?>
                            <option value="<?= $id ?>" <?= ($user['id_grupo']==$id ? 'selected' : '') ?>><?= htmlspecialchars($nombre_grupo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <hr>
            <h5>Cambiar Contraseña</h5>
            <div class="mb-3">
                <label class="form-label">Contraseña Actual</label>
                <input type="password" name="pass_actual" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" name="pass_nueva" class="form-control">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="perfil.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>

        <hr class="my-4">

        <a href="logout.php" class="btn btn-danger w-100 mb-2">Cerrar Sesión</a>

        <form id="eliminarCuentaForm" method="post">
            <input type="hidden" name="eliminar_cuenta" value="1">
            <button type="button" class="btn btn-danger w-100 mt-2" id="btnEliminar">Eliminar Cuenta</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
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

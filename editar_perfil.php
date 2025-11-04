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

// ----------------------------
// Procesar edición de perfil
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombrecompleto'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'] ?? null;
    $asignatura = $_POST['asignatura'] ?? null;
    $id_grupo = $_POST['id_grupo'] ?? null;

    // Procesar foto (si se subió una)
    $ruta_foto = $user['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $nombre_foto = time() . '_' . basename($_FILES['foto']['name']); // nombre único
        $ruta_destino = 'imagenes/' . $nombre_foto;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $ruta_foto = $ruta_destino;
        }
    }

    // Actualizar datos principales
    if ($user['rol'] === 'docente') {
        $stmt = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, asignatura=?, foto=? WHERE cedula=?");
        $stmt->bind_param("ssssss", $nombre, $apellido, $telefono, $asignatura, $ruta_foto, $cedula);
    } else {
        $stmt = $con->prepare("UPDATE usuario SET nombrecompleto=?, apellido=?, telefono=?, id_grupo=?, foto=? WHERE cedula=?");
        $stmt->bind_param("ssssss", $nombre, $apellido, $telefono, $id_grupo, $ruta_foto, $cedula);
    }
    $stmt->execute();
    $stmt->close();

    // Cambiar contraseña si corresponde
    if (!empty($_POST['pass_actual']) && !empty($_POST['pass_nueva'])) {
        $pass_actual = $_POST['pass_actual'];
        $pass_nueva = password_hash($_POST['pass_nueva'], PASSWORD_DEFAULT);

        $stmt = $con->prepare("SELECT contrasena FROM usuario WHERE cedula=?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = $result->fetch_assoc();
        $stmt->close();

        if ($datos && password_verify($pass_actual, $datos['contrasena'])) {
            $stmt = $con->prepare("UPDATE usuario SET contrasena=? WHERE cedula=?");
            $stmt->bind_param("ss", $pass_nueva, $cedula);
            $stmt->execute();
            $stmt->close();
            $mensaje = "Perfil y contraseña actualizados correctamente.";
        } else {
            $mensaje = "⚠️ La contraseña actual no es correcta.";
        }
    } else {
        $mensaje = "Perfil actualizado correctamente.";
    }

    // Recargar datos actualizados
    $stmt = $con->prepare("SELECT * FROM usuario WHERE cedula=?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
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
    border: 4px solid #588BAE;
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<?php include('footer.php'); ?>
</body>
</html>


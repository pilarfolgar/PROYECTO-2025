<?php
session_start();

// Redirige si no hay sesión activa o no es estudiante
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'estudiante') {
    header("Location: iniciosesion.php");
    exit;
}

// Incluimos la lógica que llena horarios, notificaciones y avisos
require("panel_estudiantes_logic.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Estudiantes - InfraLex</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<header class="d-flex justify-content-between align-items-center p-3 border-bottom">
    <div>
        <h1>InfraLex</h1>
        <h6>Instituto Tecnológico Superior de Paysandú</h6>
    </div>
    <div>
        <a href="indexEstudiantes.php"><img src="imagenes/LOGO.jpeg" alt="Logo" class="logo" style="height:60px;"></a>
    </div>
</header>

<nav class="nav bg-light p-2 mb-4">
    <a class="nav-link" href="horarios.php">Horarios de clase</a>
    <a class="nav-link" href="cerrar_sesion.php">Cerrar sesión</a>
</nav>

<main class="container my-5">
    <h2 class="text-center mb-4">Bienvenido/a, Estudiante</h2>

    <div class="row g-4">
        <!-- HORARIOS -->
        <div class="col-md-6">
            <div class="p-3 border rounded bg-light shadow-sm h-100">
                <h4 class="text-center mb-3">Calendario de clases</h4>
                <ul class="list-group">
                    <?php if (!empty($horarios)): ?>
                        <?php foreach ($horarios as $h): ?>
                            <li class="list-group-item">
                                📅 <?= $h['dia_semana']; ?> - <?= $h['asignatura']; ?> - <?= $h['aula']; ?> 
                                (<?= substr($h['hora_inicio'],0,5) ?> - <?= substr($h['hora_fin'],0,5) ?>)
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center">No hay clases asignadas.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- NOTIFICACIONES Y AVISOS -->
        <div class="col-md-6">
            <div class="p-3 border rounded bg-white shadow-sm h-100">
                <h4 class="text-center mb-3">Notificaciones y avisos</h4>
                <ul class="list-group">
                    <?php if (!empty($notificaciones)): ?>
                        <?php foreach ($notificaciones as $n): ?>
                            <li class="list-group-item">
                                <strong><?= $n['titulo']; ?></strong><br>
                                <?= $n['mensaje']; ?><br>
                                <small class="text-muted"><?= date("d/m/Y H:i", strtotime($n['fecha'])); ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($avisos)): ?>
                        <?php foreach ($avisos as $a): ?>
                            <li class="list-group-item list-group-item-warning">
                                <strong><?= $a['titulo']; ?></strong><br>
                                <?= $a['mensaje']; ?><br>
                                <small class="text-muted"><?= date("d/m/Y H:i", strtotime($a['fecha'])); ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (empty($notificaciones) && empty($avisos)): ?>
                        <li class="list-group-item text-center">No hay notificaciones ni avisos.</li>
                    <?php endif; ?>
                </ul>


<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Estudiantes - InfraLex</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

<header>
  <div class="HeaderIzq">
    <h1>InfraLex</h1>
    <h6>Instituto Tecnológico Superior de Paysandú</h6>
  </div>
  <div class="header-right">
    <a href="indexEstudiantes.php"><img src="imagenes/LOGO.jpeg" alt="Logo" class="logo"></a>
  </div>
</header>

<main class="container my-5">
    <h2 class="text-center mb-4">Recursos Educativos</h2>
    <p class="text-center mb-4">En esta sección encontrarás enlaces a materiales y cursos gratuitos sobre programación, bases de datos, sistemas operativos y ciberseguridad.</p>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="https://recursos.educacion.gob.ec/red/programacion-y-base-de-datos-datos-y-expresiones/" target="_blank">
                        📘 Programación y Base de Datos: Datos y expresiones
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="https://www.apuntesfpinformatica.es/" target="_blank">
                        📗 Apuntes FP Informática
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="https://www.educacionit.com/curso-de-introduccion-a-la-seguridad-informatica" target="_blank">
                        📙 Curso de Introducción a la Ciberseguridad
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="https://www.incibe.es/sites/default/files/docs/senior/guia_ciberseguridad_para_todos.pdf" target="_blank">
                        📕 Guía de Ciberseguridad para Todos
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="https://www.red-tic.unam.mx/materiales-de-capacitacion" target="_blank">
                        📒 Materiales de Capacitación TIC - UNAM
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="text-center mt-4">
    <a href="indexestudiante.php" class="btn btn-secondary">Volver al panel</a>
  </div>
</main>

<?php
require("footer.php"); // Footer de tu página
?>

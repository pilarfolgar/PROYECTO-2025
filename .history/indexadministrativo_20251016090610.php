<?php 
session_start();
require("conexion.php");
$con = conectar_bd();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrativo - InfraLex</title>

  <!-- Bootstrap + estilos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="admin-script.js"></script>
</head>

<body>
  <?php require("header.php"); ?>

  <main class="contenedor" id="gestion">
    <!-- TARJETAS PRINCIPALES -->
    <div class="tarjeta">
      <h3>Docentes</h3>
      <p>Registrar y actualizar datos de los docentes.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-docente')">➕ Agregar Docente</a>
    </div>

    <div class="tarjeta">
      <h3>Asignaturas</h3>
      <p>Crear, modificar y administrar asignaturas.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-asignatura')">➕ Agregar Asignatura</a>
    </div>

    <div class="tarjeta">
      <h3>Horarios</h3>
      <p>Organizar y actualizar los horarios de clases.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-horario')">➕ Agregar Horario</a>
    </div>

    <div class="tarjeta">
      <h3>Aulas</h3>
      <p>Administrar aulas disponibles y asignaciones.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-aula')">➕ Agregar Aula</a>
    </div>

    <div class="tarjeta">
      <h3>Grupos</h3>
      <p>Crear y administrar grupos de estudiantes.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-grupo')">➕ Agregar Grupo</a>
    </div>

    <div class="tarjeta">
      <h3>Enviar Notificación</h3>
      <p>Informar cambios, avisos o recordatorios a un grupo de estudiantes.</p>
      <a href="#" class="boton" onclick="mostrarForm('form-notificacion')">➕ Enviar Notificación</a>
    </div>

    <div class="tarjeta">
      <h3>Reportes Objetos Rotos</h3>
      <p>Ver los reportes enviados por los estudiantes.</p>
      <button class="boton" onclick="mostrarReportes()">📋 Ver Reportes</button>
    </div>

    <div class="tarjeta">
      <h3>Reservas de Aulas</h3>
      <p>Visualizar y administrar reservas de aulas.</p>
      <button class="boton" onclick="mostrarReservas()">📅 Ver Reservas</button>
    </div>

    <div class="tarjeta">
      <h3>Sugerencias de Estudiantes</h3>
      <p>Ver las sugerencias enviadas por los estudiantes.</p>
      <button class="boton" onclick="mostrarSugerencias()">💬 Ver Sugerencias</button>
    </div>
  </main>

  <!-- =======================
       FORMULARIOS (Ocultos por defecto)
  ======================= -->
  <section id="formularios-admin">
    <?php require("forms/form-docente.php"); ?>
    <?php require("forms/form-asignatura.php"); ?>
    <?php require("forms/form-horario.php"); ?>
    <?php require("forms/form-aula.php"); ?>
    <?php require("forms/form-grupo.php"); ?>
    <?php require("forms/form-notificacion.php"); ?>
  </section>

  <!-- =======================
       MODALES 
  ======================= -->
  <?php require("modales-reportes.php"); ?>
  <?php require("modales-reservas.php"); ?>
  <?php require("modales-sugerencias.php"); ?>

  <?php require("footer.php"); ?>

  <script>
  // === MOSTRAR / CERRAR FORMULARIOS ===
  function mostrarForm(id) {
    // Oculta todos los formularios
    document.querySelectorAll('.formulario').forEach(f => f.classList.add('oculto'));
    // Muestra el formulario solicitado
    const form = document.getElementById(id);
    if (form) {
      form.classList.remove('oculto');
      window.scrollTo({ top: form.offsetTop - 50, behavior: 'smooth' });
    }
  }

  function cerrarForm() {
    document.querySelectorAll('.formulario').forEach(f => f.classList.add('oculto'));
  }

  // === MODALES BOOTSTRAP ===
  function mostrarReportes() {
    new bootstrap.Modal(document.getElementById('modalReportes')).show();
  }

  function mostrarReservas() {
    new bootstrap.Modal(document.getElementById('modalReservas')).show();
  }

  function mostrarSugerencias() {
    new bootstrap.Modal(document.getElementById('modalSugerencias')).show();
  }

  // === ALERTAS SWEETALERT (SESIONES PHP) ===
  document.addEventListener('DOMContentLoaded', function() {
    <?php
    $alerts = [
        'msg_docente' => ['icon'=>'success','title'=>'¡Éxito!','text'=>'Docente registrado con éxito'],
        'error_docente'=>['icon'=>'error','title'=>'Cédula duplicada','text'=>'Ya existe un docente con esa cédula','form'=>'form-docente'],
        'msg_asignatura'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Asignatura registrada con éxito'],
        'error_asignatura'=>['icon'=>'error','title'=>'Código duplicado','text'=>'Ya existe una asignatura con ese código','form'=>'form-asignatura'],
        'msg_horario'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Horario registrado con éxito'],
        'error_horario'=>['icon'=>'error','title'=>'Horario duplicado','text'=>'Ya existe un horario con estos datos','form'=>'form-horario'],
        'msg_aula'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Aula registrada con éxito'],
        'error_aula'=>['icon'=>'error','title'=>'Error','text'=>'Ocurrió un error al registrar el aula','form'=>'form-aula'],
        'msg_notificacion'=>['icon'=>'success','title'=>'¡Éxito!','text'=>'Notificación enviada con éxito'],
        'error_notificacion'=>['icon'=>'error','title'=>'Error','text'=>'No se pudo enviar la notificación']
    ];
    foreach($alerts as $key=>$alert){
        if(isset($_SESSION[$key])){
            $form = isset($alert['form']) ? "mostrarForm('{$alert['form']}');" : "";
            echo $form."Swal.fire({icon:'{$alert['icon']}',title:'{$alert['title']}',text:'{$alert['text']}',timer:2500,showConfirmButton:false});";
            unset($_SESSION[$key]);
        }
    }
    ?>
  });
  </script>
</body>
</html>

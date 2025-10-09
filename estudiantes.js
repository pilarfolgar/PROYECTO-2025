// Mostrar y cerrar formulario
const btnAbrir = document.getElementById('btnAbrirReporte');
const btnCerrar = document.getElementById('btnCerrarReporte');
const formSection = document.getElementById('form-reporte');
const overlay = document.getElementById('overlayReporte');
const form = document.getElementById('reporteForm');

btnAbrir.addEventListener('click', () => {
  formSection.style.display = 'block';
  overlay.style.display = 'block';
});

btnCerrar.addEventListener('click', () => {
  formSection.style.display = 'none';
  overlay.style.display = 'none';
});

// Validación del formulario
form.addEventListener('submit', function (event) {
  const fecha = document.getElementById('fechaReporte');
  const hoy = new Date().toISOString().split('T')[0];

  if (fecha.value > hoy) {
    fecha.setCustomValidity('No puede seleccionar una fecha futura.');
  } else {
    fecha.setCustomValidity('');
  }

  if (!form.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
  }

  form.classList.add('was-validated');
});

// Notificación aula dinámica
const aulas = ['Aula 10 - Planta Baja', 'Aula 11 - Segundo piso', 'Aula 12 - Segundo piso', 'Aula 14 - Tercer piso'];
const randomAula = aulas[Math.floor(Math.random() * aulas.length)];
document.getElementById("notificacionAula").innerHTML = `📢 Hoy te toca clase en: <strong>${randomAula}</strong>`;

// Efecto del botón flotante
const btnFlotante = document.getElementById('btnAbrirReporte');
btnFlotante.addEventListener('mouseenter', () => btnFlotante.classList.add('btn-flotante-hover'));
btnFlotante.addEventListener('mouseleave', () => btnFlotante.classList.remove('btn-flotante-hover'));
document.addEventListener("DOMContentLoaded", function() {

  const cedula = "<?php echo $_SESSION['cedula']; ?>"; // Opcionalmente se puede pasar desde PHP

  // --- NOTIFICACIONES EN TIEMPO REAL ---
  function cargarNotificaciones() {
    fetch('notificaciones.php')
      .then(res => res.json())
      .then(data => {
        const notiList = document.getElementById("notificaciones");
        notiList.innerHTML = "";
        data.forEach(noti => {
          const li = document.createElement("li");
          li.classList.add("list-group-item");
          li.innerHTML = noti.leido ? noti.mensaje : `<strong>${noti.mensaje}</strong>`;
          li.onclick = () => marcarLeida(noti.id);
          notiList.appendChild(li);
        });
      });
  }

  function marcarLeida(id) {
    fetch('marcarLeida.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `id=${id}`
    }).then(() => cargarNotificaciones());
  }

  setInterval(cargarNotificaciones, 5000); // cada 5 segundos
  cargarNotificaciones();

  // --- CHAT / FORO ---
  const chatBox = document.getElementById("chatBox");
  const mensajeInput = document.getElementById("mensajeInput");
  const enviarMensaje = document.getElementById("enviarMensaje");

  function cargarMensajes() {
    fetch('chat.php')
      .then(res => res.json())
      .then(data => {
        chatBox.innerHTML = "";
        data.forEach(msg => {
          const div = document.createElement("div");
          div.innerHTML = `<strong>${msg.usuario}:</strong> ${msg.mensaje} <small class="text-muted">[${msg.fecha}]</small>`;
          chatBox.appendChild(div);
        });
        chatBox.scrollTop = chatBox.scrollHeight;
      });
  }

  enviarMensaje.addEventListener("click", function() {
    if(mensajeInput.value.trim() === "") return;
    fetch('enviarMensaje.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `mensaje=${encodeURIComponent(mensajeInput.value)}`
    }).then(() => {
      mensajeInput.value = "";
      cargarMensajes();
    });
  });

  setInterval(cargarMensajes, 3000); // cada 3 segundos
  cargarMensajes();

});


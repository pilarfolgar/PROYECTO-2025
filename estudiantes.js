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

document.addEventListener("DOMContentLoaded", () => {

  // ==================== Temporizador Pomodoro ====================
  let time = 25 * 60; // 25 minutos
  let interval;
  const timerEl = document.getElementById("timer");
  const startBtn = document.getElementById("startTimer");
  const pauseBtn = document.getElementById("pauseTimer");
  const resetBtn = document.getElementById("resetTimer");

  function updateTimer() {
    let min = Math.floor(time / 60);
    let sec = time % 60;
    timerEl.textContent = `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;
  }

  startBtn.addEventListener("click", () => {
    if (!interval) {
      interval = setInterval(() => {
        if (time > 0) {
          time--;
          updateTimer();
        } else {
          clearInterval(interval);
          interval = null;
          alert("⏰ Tiempo de estudio terminado!");
        }
      }, 1000);
    }
  });

  pauseBtn.addEventListener("click", () => {
    clearInterval(interval);
    interval = null;
  });

  resetBtn.addEventListener("click", () => {
    clearInterval(interval);
    interval = null;
    time = 25 * 60;
    updateTimer();
  });

  updateTimer();

  // ==================== Notas rápidas con LocalStorage ====================
  const notesEl = document.getElementById("quickNotes");
  const saveBtn = document.getElementById("saveNotes");
  const msg = document.getElementById("notesMsg");

  if(localStorage.getItem("estudianteNotas")){
    notesEl.value = localStorage.getItem("estudianteNotas");
  }

  saveBtn.addEventListener("click", () => {
    localStorage.setItem("estudianteNotas", notesEl.value);
    msg.textContent = "✅ Notas guardadas localmente!";
    setTimeout(() => msg.textContent = "", 2000);
  });

  // ==================== Resaltado automático de la clase del día ====================
  const items = document.querySelectorAll(".list-group-item");
  const dias = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
  const now = new Date();
  const hoy = dias[now.getDay()];
  const horaActual = now.getHours() + ":" + now.getMinutes().toString().padStart(2,'0');

  items.forEach(item => {
    if(item.textContent.includes(hoy)) {
      item.style.backgroundColor = "#d1e7dd"; // verde claro
      item.style.fontWeight = "bold";
    }
  });

});

// Abrir y cerrar formulario de sugerencia
const abrirBtn = document.getElementById("abrirSugerencia");
const cerrarBtn = document.getElementById("cerrarSugerencia");
const overlaySugg = document.getElementById("overlaySugerencia");
const formSugerencia = document.getElementById("form-sugerencia");

abrirBtn.addEventListener("click", () => {
  overlaySugg.style.display = "block";
  formSugerencia.style.display = "block";
});

cerrarBtn.addEventListener("click", () => {
  overlaySugg.style.display = "none";
  formSugerencia.style.display = "none";
});

// Enviar formulario con fetch
const sugerenciaForm = document.getElementById("sugerenciaForm");
sugerenciaForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const mensaje = document.getElementById("mensajeSugerencia").value.trim();

  if (mensaje.length < 5) {
    alert("El mensaje debe tener al menos 5 caracteres.");
    return;
  }

  fetch("guardar-sugerencia.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `mensaje=${encodeURIComponent(mensaje)}`
  })
  .then(res => res.text())
  .then(res => {
    if (res === "ok") {
      alert("Sugerencia enviada con éxito");
      sugerenciaForm.reset();
      overlaySugg.style.display = "none";
      formSugerencia.style.display = "none";
    } else {
      alert("Error al enviar la sugerencia, intente nuevamente.");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error de conexión.");
  });
});

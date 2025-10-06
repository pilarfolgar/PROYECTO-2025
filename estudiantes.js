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

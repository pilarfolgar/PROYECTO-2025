// Mostrar y cerrar formulario de reporte
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

// Validación del formulario de reporte
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

// Efecto del botón flotante
btnAbrir.addEventListener('mouseenter', () => btnAbrir.classList.add('btn-flotante-hover'));
btnAbrir.addEventListener('mouseleave', () => btnAbrir.classList.remove('btn-flotante-hover'));

// Mostrar/ocultar horario del grupo
document.getElementById('verHorarioBtn').addEventListener('click', function(e){
    e.preventDefault();
    const horario = document.getElementById('horarioGrupo');
    horario.style.display = horario.style.display === 'none' ? 'block' : 'none';
    horario.scrollIntoView({behavior: "smooth"});
});

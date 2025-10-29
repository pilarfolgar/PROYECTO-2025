// Mostrar y cerrar formulario de reporte
const btnAbrir = document.getElementById('btnAbrirReporte');
const btnCerrar = document.getElementById('btnCerrarReporte');
const formSection = document.getElementById('form-reporte');
const overlay = document.getElementById('overlayReporte');

btnAbrir.addEventListener('click', () => {
  formSection.classList.add('visible');
  overlay.style.display = 'block';
});

btnCerrar.addEventListener('click', () => {
  formSection.classList.remove('visible');
  overlay.style.display = 'none';
});

// Cerrar formulario al hacer click en overlay
overlay.addEventListener('click', () => {
  formSection.classList.remove('visible');
  overlay.style.display = 'none';
});

overlay.classList.add('visible'); // al abrir
overlay.classList.remove('visible'); // al cerrar


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

document.addEventListener("DOMContentLoaded", function() {
    const botonesDia = document.querySelectorAll(".btn-dia");
    const horariosDias = document.querySelectorAll(".horario-dia");

    botonesDia.forEach(btn => {
        btn.addEventListener("click", () => {
            // Quitar active de todos los botones
            botonesDia.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            // Ocultar todos los horarios
            horariosDias.forEach(h => h.classList.add("d-none"));

            // Mostrar solo el horario correspondiente
            const dia = btn.getAttribute("data-dia");
            const contenedor = document.getElementById("horario-" + dia);
            if (contenedor) contenedor.classList.remove("d-none");
        });
    });
});

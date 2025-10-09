// Funciones para mostrar/ocultar forms (como modales light)
function mostrarForm(formId) {
  // Oculta todos los forms
  document.querySelectorAll('.formulario').forEach(form => {
    form.style.display = 'none';
  });
  // Muestra el seleccionado
  const form = document.getElementById(formId);
  form.style.display = 'flex';  // Flex para centrar
  document.body.style.overflow = 'hidden';  // Previene scroll de fondo
  // Focus en primer input/select
  const firstInput = form.querySelector('input, select');
  if (firstInput) firstInput.focus();
}

function cerrarForm(formId) {
  const form = document.getElementById(formId);
  form.style.display = 'none';
  document.body.style.overflow = 'auto';  // Restaura scroll
}

// Clic fuera del form para cerrar (opcional, como modal)
document.addEventListener('click', function(e) {
  const form = e.target.closest('.formulario');
  if (form && !e.target.closest('form')) {  // Clic en overlay, no en form
    cerrarForm(form.id);
  }
});

// ESC para cerrar cualquier form abierto
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const openForm = document.querySelector('.formulario[style*="display: flex"], .formulario[style*="display: block"]');
    if (openForm) {
      cerrarForm(openForm.id);
    }
  }
});
function cargarAsignaturas(id_curso) {
    const select = document.getElementById('asignaturasAsignatura');
    select.innerHTML = '<option value="">Cargando...</option>';
    fetch('obtener-asignaturas-curso.php?id_curso=' + id_curso)
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '';
            if(data.length === 0){
                select.innerHTML = '<option value="">No hay asignaturas para este curso</option>';
            } else {
                data.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id_asignatura;
                    opt.textContent = a.nombre;
                    select.appendChild(opt);
                });
            }
        });
}

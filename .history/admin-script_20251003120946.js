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
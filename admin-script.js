// =============================
// FUNCIONES PARA FORMULARIOS MODALES
// =============================

// Muestra el formulario como modal centrado
function mostrarForm(formId) {
  // Oculta todos los formularios abiertos
  document.querySelectorAll('.formulario').forEach(form => {
    form.style.display = 'none';
  });

  // Muestra el seleccionado
  const form = document.getElementById(formId);
  const overlay = document.getElementById('overlayForm');

  if (form) {
    form.style.display = 'flex'; // Flex para centrar
    document.body.style.overflow = 'hidden'; // Bloquear scroll del fondo
  }

  if (overlay) {
    overlay.style.display = 'block'; // Mostrar fondo oscuro
  }

  // Focus en primer input o select
  const firstInput = form.querySelector('input, select, textarea');
  if (firstInput) firstInput.focus();
}

// Cierra el formulario actual
function cerrarForm(formId) {
  const form = document.getElementById(formId);
  const overlay = document.getElementById('overlayForm');

  if (form) form.style.display = 'none';
  if (overlay) overlay.style.display = 'none';

  document.body.style.overflow = 'auto'; // Restaurar scroll del fondo
}

// Clic en overlay para cerrar
document.addEventListener('click', function (e) {
  const overlay = document.getElementById('overlayForm');
  const openForm = document.querySelector('.formulario[style*="display: flex"], .formulario[style*="display: block"]');

  // Si se hace clic fuera del formulario (en el overlay)
  if (overlay && e.target === overlay && openForm) {
    cerrarForm(openForm.id);
  }
});

// Presionar ESC para cerrar cualquier form abierto
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    const openForm = document.querySelector('.formulario[style*="display: flex"], .formulario[style*="display: block"]');
    if (openForm) {
      cerrarForm(openForm.id);
    }
  }
});

// =============================
// CARGAR ASIGNATURAS POR CURSO
// =============================
function cargarAsignaturas(id_curso) {
  const select = document.getElementById('asignaturasAsignatura');
  if (!select) return;

  select.innerHTML = '<option value="">Cargando...</option>';

  fetch('obtener-asignaturas-curso.php?id_curso=' + id_curso)
    .then(res => res.json())
    .then(data => {
      select.innerHTML = '';

      if (!data || data.length === 0) {
        select.innerHTML = '<option value="">No hay asignaturas para este curso</option>';
      } else {
        data.forEach(a => {
          const opt = document.createElement('option');
          opt.value = a.id_asignatura;
          opt.textContent = a.nombre;
          select.appendChild(opt);
        });
      }
    })
    .catch(() => {
      select.innerHTML = '<option value="">Error al cargar asignaturas</option>';
    });
}

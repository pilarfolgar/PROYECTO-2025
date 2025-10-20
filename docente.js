// ==============================
// Ver miembros (AJAX dinámico)
// ==============================
document.querySelectorAll('.ver-miembros').forEach(btn => {
  btn.addEventListener('click', async () => {
    const grupoId = btn.dataset.grupo;
    const ul = document.getElementById('miembros-' + grupoId);

    if (ul.style.display === 'block') {
      ul.style.display = 'none';
      btn.textContent = 'Ver miembros';
      return;
    }

    btn.textContent = 'Cargando...';
    try {
      const res = await fetch(`indexdocente.php?ajax=miembros&id_grupo=${grupoId}`);
      const data = await res.json();
      ul.innerHTML = '';

      if (data.length > 0) {
        data.forEach(m => {
          const li = document.createElement('li');
          li.textContent = `${m.nombrecompleto} ${m.apellido}`;
          ul.appendChild(li);
        });
      } else {
        ul.innerHTML = '<li class="text-muted">Sin estudiantes registrados</li>';
      }
      ul.style.display = 'block';
      btn.textContent = 'Ocultar miembros';
    } catch (error) {
      console.error(error);
      btn.textContent = 'Error';
    }
  });
});

// ==============================
// Reservar bloque (demo)
// ==============================
function abrirReservaBloque(td) {
  const aula = td.dataset.aula;
  const hora = td.dataset.hora;
  alert('Abrir modal para reservar aula ' + aula + ' a las ' + hora);
}

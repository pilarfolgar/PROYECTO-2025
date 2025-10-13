document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menu-btn');
  const menuDropdown = document.getElementById('menu-dropdown');
  const themeToggle = document.getElementById('toggle-theme');
  const notifBtn = document.getElementById('btn-notif');

  // Menú hamburguesa
  if (menuBtn && menuDropdown) {
    menuBtn.addEventListener('click', () => {
      menuBtn.classList.toggle('active');
      menuDropdown.classList.toggle('show');
    });
  }

  // Modo oscuro
  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      themeToggle.textContent = document.body.classList.contains('dark-mode') ? '☀️ Modo claro' : '🌙 Modo oscuro';
    });
  }

  // Notificaciones SweetAlert
  if (notifBtn) {
    notifBtn.addEventListener('click', () => {
      Swal.fire({
        title: 'Notificaciones',
        html: `
          <ul style="text-align:left; list-style:none; padding:0;">
            <li>📅 Nueva clase programada</li>
            <li>📢 Actualización del sistema</li>
            <li>✅ Registro docente aprobado</li>
          </ul>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar',
        background: document.body.classList.contains('dark-mode') ? '#333' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f1f1f1' : '#000'
      });
    });
  }
});

if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    themeToggle.textContent = document.body.classList.contains('dark-mode') 
      ? '☀️ Modo claro' 
      : '🌙 Modo oscuro';
  });
}

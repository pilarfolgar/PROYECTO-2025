document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menu-btn');
  const menuDropdown = document.getElementById('menu-dropdown');
  const themeToggle = document.getElementById('toggle-theme');

  // Menu hamburguesa
  if (menuBtn && menuDropdown) {
    menuBtn.addEventListener('click', () => {
      menuBtn.classList.toggle('active');
      menuDropdown.classList.toggle('show');
    });
  }

  // Modo oscuro
  if (themeToggle) {
    themeToggle.addEventListener('click', (e) => {
      e.preventDefault();
      document.body.classList.toggle('dark-mode');
      themeToggle.textContent = document.body.classList.contains('dark-mode') 
        ? '☀️ Modo claro' 
        : '🌙 Modo oscuro';
    });
  }
});

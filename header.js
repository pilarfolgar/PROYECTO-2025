const btnMenu = document.getElementById('menuBtn');
const menu = document.getElementById('menu');

btnMenu.onclick = () => {
  menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
};
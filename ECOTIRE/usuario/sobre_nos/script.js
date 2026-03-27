document.querySelector('.icon').addEventListener('click', function() {
    document.querySelector('.menu-horizontal').classList.toggle('show');
});

const btnMobile = document.querySelector('.hamburger');
const menu = document.querySelector('.nav-menu');

btnMobile.addEventListener('click', () => {
  btnMobile.classList.toggle('active');
  menu.classList.toggle('active');
});

// Opcional: Fecha o menu ao clicar em qualquer link
document.querySelectorAll('.nav-menu a').forEach(link => {
  link.addEventListener('click', () => {
    btnMobile.classList.remove('active');
    menu.classList.remove('active');
  });
});
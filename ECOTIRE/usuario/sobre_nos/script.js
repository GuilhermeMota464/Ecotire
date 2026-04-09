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

// Pesquisa auto (fixed: selector, param, path, HTML response)
const inputBusca =  document.getElementById('busca');
const divResultado = document.getElementById('resultado');

inputBusca.addEventListener('input', async () => {
    const query = inputBusca.value;
    if(query.length < 2){
        divResultado.style.display = 'none';
        return;
    }

    // Chamada AJAX para o arquivo PHP
    const response = await fetch(`../../funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
    const htmlResultados = await response.text();

    if (htmlResultados && htmlResultados.trim() !== 'Nenhum resultado encontrado') {
        divResultado.innerHTML = htmlResultados;
        divResultado.style.display = 'block';
    } else{
        divResultado.style.display = 'none';
    }
});
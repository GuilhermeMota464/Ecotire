document.addEventListener("DOMContentLoaded", () => {
    const iconMenu = document.querySelector('.icon');
    const menuHorizontal = document.querySelector('.menu-horizontal');
    if (iconMenu && menuHorizontal) {
        iconMenu.addEventListener('click', function () {
            menuHorizontal.classList.toggle('show');
        });
    }
    const btnMobile = document.querySelector('.hamburger');
    const menu = document.querySelector('.nav-menu');
    if (btnMobile && menu) {
        btnMobile.addEventListener('click', () => {
            btnMobile.classList.toggle('active');
            menu.classList.toggle('active');
        });
    }
    const linksMenu = document.querySelectorAll('.nav-menu a');
    if (linksMenu.length > 0 && btnMobile && menu) {
        linksMenu.forEach(link => {
            link.addEventListener('click', () => {
                btnMobile.classList.remove('active');
                menu.classList.remove('active');
            });
        });
    }
});

// Pesquisa auto 
const inputBusca = document.getElementById('busca');
const divResultado = document.getElementById('resultado');
const iconeLupa = document.getElementById('lupa');

inputBusca.addEventListener('input', async () => {
    const query = inputBusca.value;
    if(query.length < 2){
        divResultado.style.display = 'none';
        inputBusca.classList.remove('busca-ativa-input');
        iconeLupa.classList.remove('busca-ativa-icone');
        return;
    }
    // AJAX
    const response = await fetch(`../../funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
    const htmlResultados = await response.text();
    
    if (htmlResultados && htmlResultados.trim() !== 'Nenhum resultado encontrado') {
        divResultado.innerHTML = htmlResultados;
        divResultado.style.display = 'block';
        inputBusca.classList.add('busca-ativa-input');
        iconeLupa.classList.add('busca-ativa-icone');
    } else {
        divResultado.style.display = 'none';
        inputBusca.classList.remove('busca-ativa-input');
        iconeLupa.classList.remove('busca-ativa-icone');
    }

});

document.getElementById('botaoCarrinho').addEventListener('click', function() {
    fetch('../../funcoesPHP/verificarSessao.php')
    .then(response => response.json())
    .then(data => {
        if (data.logado) {
            window.location.href = '../carrinho/carrinho.php';
        } else {
            Swal.fire({
                title: 'Acesso Restrito',
                text: 'Você precisa fazer login para ver seu carrinho.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Fazer Login',
                cancelButtonText: 'Depois'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../carrinho/carrinho.php';
                }
            });
        }
    })
    .catch(error => {
        console.error('Erro ao verificar sessão:', error);
    });
});

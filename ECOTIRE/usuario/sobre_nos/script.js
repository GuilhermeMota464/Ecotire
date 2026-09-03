function toggleMenu() {
    const menu = document.getElementById("menu-links");
    const icon = document.querySelector("#icon i");
    
    // Alterna a classe 'mostrar' criada no CSS
    menu.classList.toggle("mostrar");

    // Muda o ícone de barras (hambúrguer) para 'X' ao abrir o menu
    if (menu.classList.contains("mostrar")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-xmark");
    } else {
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
    }
}

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

//pesquisa
const inputBusca = document.getElementById('busca');
const divResultado = document.getElementById('resultado');

if (inputBusca && divResultado) {
    inputBusca.addEventListener('input', async () => {
        const query = inputBusca.value.trim();
        console.log(query);
        if (query.length < 2) {
            divResultado.style.display = 'none';
            return;
        }
        try {
            const response = await fetch(`/ecotire/ecotire/funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
            const html = await response.text();
            console.log(html);
            divResultado.innerHTML = html;
            divResultado.style.display = 'block';
        } catch (erro) {
            console.log(erro);
        }
    });
}

function fecharBusca() {
    if (divResultado) divResultado.style.display = 'none';
    if (inputBusca) inputBusca.classList.remove('busca-ativa-input');
    if (iconeLupa) iconeLupa.classList.remove('busca-ativa-icone');
}

document.addEventListener('click', (e) => {
    if (inputBusca && !inputBusca.contains(e.target) && !divResultado.contains(e.target)) {
        fecharBusca();
    }
});

// Verificação do Carrinho
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
                        // Redireciona para login ou para a página que você desejar
                        window.location.href = '../login/login.php'; 
                    }
                });
            }
        })
        .catch(erro => {
            console.error("Erro ao verificar sessão:", erro);
        });
});

function toggleMenu() {
    const menu = document.getElementById("menu-links");
    const icon = document.querySelector("#icon i");

    menu.classList.toggle("mostrar");

    if (menu.classList.contains("mostrar")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-xmark");
    } else {
        icon.classList.remove("fa-xmark");
        icon.classList.add("fa-bars");
    }
}

// ===== Pesquisa =====
const inputBusca = document.getElementById('busca');
const divResultado = document.getElementById('resultado');

if (inputBusca && divResultado) {
    inputBusca.addEventListener('input', async () => {
        const query = inputBusca.value.trim();
        if (query.length < 2) {
            divResultado.style.display = 'none';
            return;
        }
        try {
            const response = await fetch(`/ecotire/ecotire/funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
            const html = await response.text();
            divResultado.innerHTML = html;
            divResultado.style.display = 'block';
        } catch (erro) {
            console.log(erro);
        }
    });
}

function fecharBusca() {
    if (divResultado) divResultado.style.display = 'none';
}

document.addEventListener('click', (e) => {
    if (inputBusca && divResultado && !inputBusca.contains(e.target) && !divResultado.contains(e.target)) {
        fecharBusca();
    }
});

// ===== Botão Voltar ao Topo =====
const btnTopo = document.getElementById('btnTopo');

if (btnTopo) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            btnTopo.classList.add('mostrar');
        } else {
            btnTopo.classList.remove('mostrar');
        }
    });

    btnTopo.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
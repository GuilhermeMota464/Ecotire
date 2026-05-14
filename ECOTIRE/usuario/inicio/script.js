function toggleMenu() {
    const menu = document.getElementById("menu-links");
    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    };

    if (menu.style.paddingBottom === "0px") {
        menu.style.paddingBottom = "10px";
    }else {
        menu.style.paddingBottom = "0px";
    };
    const icon = document.getElementById("icon");
    if (icon.style.backgroundColor === "var(--cor-primaria-escura)") {
        icon.style.backgroundColor = "var(--cor-primaria)";
    }else{
        icon.style.backgroundColor = "var(--cor-primaria-escura)"
    }   
}


document.getElementById("telefone").addEventListener("input", function () {

    this.value = this.value.replace(/\D/g, "");
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
    let formattedValue = this.value;
    if (formattedValue.length > 0) {
        formattedValue = "(" + formattedValue;
    }
    if (formattedValue.length > 3) {
        formattedValue = formattedValue.slice(0, 3) + ") " + formattedValue.slice(3);
    }
    if (formattedValue.length > 10) {
        formattedValue = formattedValue.slice(0, 10) + "-" + formattedValue.slice(10);
    }
    this.value = formattedValue;
});

let slides = document.querySelector(".Slide");
let index = 0;

const total = slides.children.length;

let intervalo = setInterval(proximoSlide, 3000);

function proximoSlide() {
    index++;
    moverSlide();
}

function slideAnterior() {
    index--;
    if (index < 0) index = total - 2; // volta pro último real
    moverSlide();
}

function moverSlide() {
    slides.style.transition = "transform 0.8s ease-in-out";
    slides.style.transform = `translateX(-${index * 25}%)`;

    // loop infinito (clone)
    if (index === total - 1) {
        setTimeout(() => {
            slides.style.transition = "none";
            slides.style.transform = "translateX(0)";
            index = 0;
        }, 800);
    }
}

function resetIntervalo() {
    clearInterval(intervalo);
    intervalo = setInterval(proximoSlide, 3000);
}

document.querySelector(".next").addEventListener("click", () => {
    proximoSlide();
    resetIntervalo();
});

document.querySelector(".prev").addEventListener("click", () => {
    slideAnterior();
    resetIntervalo();
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

function toggleMenu() {
    const menu = document.getElementById("menu-links");
    const icon = document.getElementById("icon");
    
    const isVisible = menu.style.display === "block";
    
    menu.style.display = isVisible ? "none" : "block";
    menu.style.paddingBottom = isVisible ? "0px" : "10px";

    if (icon) {
        if (!isVisible) {
            icon.style.backgroundColor = "var(--cor-primaria-escura)";
        } else {
            icon.style.backgroundColor = "var(--cor-primaria)";
        }
    }
}

document.getElementById("telefone").addEventListener("input", function (e) {
    let x = e.target.value.replace(/\D/g, "").match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
    
    e.target.value = !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
});

let slidesContainer = document.querySelector(".Slide");
let slideImages = document.querySelectorAll(".Slide img");
let currentIndex = 0;
const totalSlides = slideImages.length;

const step = 100 / totalSlides;

let slideInterval = setInterval(proximoSlide, 3000);

function moverSlide() {
    slidesContainer.style.transition = "transform 0.8s ease-in-out";
    slidesContainer.style.transform = `translateX(-${currentIndex * step}%)`;
}

function proximoSlide() {
    currentIndex++;
    
    if (currentIndex >= totalSlides) {
        slidesContainer.style.transition = "none";
        currentIndex = 0;
        slidesContainer.style.transform = "translateX(0)";
        setTimeout(() => {
            currentIndex = 1;
            moverSlide();
        }, 50);
    } else {
        moverSlide();
    }
}

function slideAnterior() {
    currentIndex--;
    if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    }
    moverSlide();
}

function resetIntervalo() {
    clearInterval(slideInterval);
    slideInterval = setInterval(proximoSlide, 3000);
}
document.querySelector(".next").addEventListener("click", () => {
    proximoSlide();
    resetIntervalo();
});

document.querySelector(".prev").addEventListener("click", () => {
    slideAnterior();
    resetIntervalo();
});

const inputBusca = document.getElementById('busca');
const divResultado = document.getElementById('resultado');
const iconeLupa = document.getElementById('lupa');

if (inputBusca) {
    inputBusca.addEventListener('input', async () => {
        const query = inputBusca.value.trim();
        
        // Se a busca for pequena, limpa os resultados e sai da função
        if (query.length < 2) {
            fecharBusca();
            return;
        }

        try {
            // Verifica se o caminho do arquivo PHP está correto (../../funcoesPHP/busca.php)
            const response = await fetch(`../../funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
            
            if (!response.ok) throw new Error('Erro na requisição');

            const htmlResultados = await response.text();
            
            // Se o PHP retornar algo válido
            if (htmlResultados.trim() !== '' && !htmlResultados.includes('Nenhum resultado encontrado')) {
                divResultado.innerHTML = htmlResultados;
                divResultado.style.display = 'block';
                
                // Adiciona as classes de estilo ativo
                inputBusca.classList.add('busca-ativa-input');
                if (iconeLupa) iconeLupa.classList.add('busca-ativa-icone');
            } else {
                fecharBusca();
            }
        } catch (error) {
            console.error("Erro ao buscar dados:", error);
            fecharBusca();
        }
    });
}

// Função para limpar a busca quando estiver vazia
function fecharBusca() {
    if (divResultado) divResultado.style.display = 'none';
    if (inputBusca) inputBusca.classList.remove('busca-ativa-input');
    if (iconeLupa) iconeLupa.classList.remove('busca-ativa-icone');
}

// Fechar a busca se o usuário clicar fora dela
document.addEventListener('click', (e) => {
    if (inputBusca && !inputBusca.contains(e.target) && !divResultado.contains(e.target)) {
        fecharBusca();
    }
});
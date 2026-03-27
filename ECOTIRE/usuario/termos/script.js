// Obtém o botão
const btn = document.getElementById("btnTopo");

// Quando o usuário rolar a página, verifica se deve mostrar o botão
window.onscroll = function() {
    if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 100) {
        btn.style.display = "flex"; // Mostra
    } else {
        btn.style.display = "none"; // Esconde
    }
};

// Quando o usuário clicar, rola suavemente para o topo
btn.addEventListener("click", function(){
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});
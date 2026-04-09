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

// Pesquisa auto (fixed: selector, param, path, HTML response)
const inputBusca =  document.getElementById('busca');
const divResultado = document.getElementById('resultado');
const iconeLupa = document.getElementById('lupa');

inputBusca.addEventListener('input', async () => {
    const query = inputBusca.value;
    if(query.length < 2){
        divResultado.style.display = 'none';
        inputBusca.style.borderRadius = '5px 0px 0px 5px';
        iconeLupa.style.borderRadius = '0px 5px 5px 0px';
        return;
    }

    // Chamada AJAX para o arquivo PHP
    const response = await fetch(`../../funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
    const htmlResultados = await response.text();

    if (htmlResultados && htmlResultados.trim() !== 'Nenhum resultado encontrado') {
        divResultado.innerHTML = htmlResultados;
        divResultado.style.display = 'block';
        inputBusca.style.borderRadius = '5px 0px 0px 0px';
        iconeLupa.style.borderRadius = '0px 5px 5px 0px';
    } else{
        divResultado.style.display = 'none';
        inputBusca.style.borderRadius = '5px 0px 0px 5px';
        iconeLupa.style.borderRadius = '0px 5px 5px 0px';
    }
});
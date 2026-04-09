function favoritarProduto(coracao) {
    coracao.classList.toggle('liked');

    if (coracao.classList.contains('liked')) {
        coracao.classList.remove('fa-regular');
        coracao.classList.add('fa-solid');
        coracao.style.color = '#e3262e';
    } else {
        coracao.classList.remove('fa-solid');
        coracao.classList.add('fa-regular');
        coracao.style.color = '';
    }
}

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
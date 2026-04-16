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
})

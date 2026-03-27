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
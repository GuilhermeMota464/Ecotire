function ChangeImage(src) {
    const mainImage = document.getElementById('produto-principal');
    mainImage.src = src;
    
    // Remove classe 'selecionada' de todas as miniaturas
    const thumbnails = document.querySelectorAll('.produtos-imagem');
    thumbnails.forEach(thumb => thumb.classList.remove('selecionada'));
    
    // Adiciona classe 'selecionada' à miniatura clicada
    const clickedThumbnail = document.querySelector(`.produtos-imagem[src="${src}"]`);
    if (clickedThumbnail) {
        clickedThumbnail.classList.add('selecionada');
    }
}

document.getElementById
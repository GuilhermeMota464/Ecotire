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

document.getElementById('form-carrinho').addEventListener('submit', function(e){
    e.preventDefault(); //impede que a pagina mude

    const formData = new FormData(this);

    fetch('../../funcoesPHP/addCarrinho.php',{
        method: 'POST',
        body: formData
    }) .then(response => {
        if(response.ok){
            Swal.fire({
                title: 'Sucesso!',
                text: 'Produto adicionado ao carrinho.',
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Continuar comprando'
            });
        } else {
            Swal.fire({
                title: 'Erro!',
                text: 'Não foi possível adicionar o produto.',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error', error);
        Swal.fire('Erro!', 'Falha na comunicação com o servidor.', 'error');
    });
});
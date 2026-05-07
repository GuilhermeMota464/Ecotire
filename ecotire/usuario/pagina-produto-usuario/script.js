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
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch('../../funcoesPHP/addCarrinho.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.status === 401) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Você precisa estar logado para adicionar itens ao carrinho.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ir para Login',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../login/login.php'; 
                }
            });
            return null; 
        }

        if (!response.ok) {
            throw new Error('Erro na rede');
        }
        return response.json(); 
    })
    .then(data => {
        if (data) { 
            Swal.fire({
                title: 'Sucesso!',
                text: 'Produto adicionado ao carrinho.',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
});
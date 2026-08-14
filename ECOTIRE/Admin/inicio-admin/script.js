document.addEventListener('DOMContentLoaded', () => {
    const InputFile = document.querySelector("#inserir-imagem");
    const PictureContainer = document.querySelector(".picture");
    const openModalBtn = document.getElementById('openModalBtn');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const productModal = document.getElementById('productModal');
    const modalClose = document.getElementById('closeModalBtn');
    const form = document.getElementById('form-produto');
    const promoCheckbox = document.getElementById('promo');
    const precoPromoContainer = document.getElementById('container-preco-promo');
    const precoPromoInput = document.getElementById('preco_promo');

    // Pré-visualização da imagem no Modal
    if (InputFile && PictureContainer) {
        InputFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const pictureImage = PictureContainer.querySelector('.picture-image');

            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function(e) {
                    const oldImg = PictureContainer.querySelector('img');
                    if (oldImg) oldImg.remove();

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-img';

                    if (pictureImage) pictureImage.style.display = 'none';
                    PictureContainer.appendChild(img);
                });
                reader.readAsDataURL(file);
            }
        });
    }

    // Abertura do Modal
    function openModal() {
        form.reset();
        const pictureImage = PictureContainer.querySelector('.picture-image');
        const existingImg = PictureContainer.querySelector('img');
        
        if (existingImg) existingImg.remove();
        if (pictureImage) pictureImage.style.display = 'block';

        precoPromoContainer.style.display = 'none';
        precoPromoInput.removeAttribute('required');

        modalBackdrop.classList.add('open');
        productModal.classList.add('open');
    }

    // Fechamento do Modal
    function closeModal() {
        modalBackdrop.classList.remove('open');
        productModal.classList.remove('open');
    }

    if (openModalBtn) openModalBtn.addEventListener('click', openModal);
    if (modalClose) modalClose.addEventListener('click', closeModal);

    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) closeModal();
    });

    // Exibição do campo de preço promocional
    if (promoCheckbox) {
        promoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                precoPromoContainer.style.display = 'block';
                precoPromoInput.setAttribute('required', 'true');
            } else {
                precoPromoContainer.style.display = 'none';
                precoPromoInput.removeAttribute('required');
                precoPromoInput.value = '';
            }
        });
    }
});
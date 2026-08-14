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

    // Pré-visualização da imagem enviada
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
                    PictureContainer.style.border = "2px solid var(--cor-primaria)";
                });
                reader.readAsDataURL(file);
            }
        });
    }

    // Função de Abertura do Modal e Reset
    function openModal() {
        form.reset();
        
        // Reset do visual da Imagem
        const pictureImage = PictureContainer.querySelector('.picture-image');
        const existingImg = PictureContainer.querySelector('img');
        
        if (existingImg) existingImg.remove();
        if (pictureImage) pictureImage.style.display = 'block';

        PictureContainer.style.border = "2px dashed var(--borda)";
        precoPromoContainer.style.display = 'none';
        precoPromoInput.removeAttribute('required');

        modalBackdrop.classList.add('open');
        productModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    // Função de Fechamento do Modal
    function closeModal() {
        modalBackdrop.classList.remove('open');
        productModal.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (openModalBtn) openModalBtn.addEventListener('click', openModal);
    if (modalClose) modalClose.addEventListener('click', closeModal);

    // Fechar ao clicar fora do modal
    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) closeModal();
    });

    // Fechar ao pressionar a tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && productModal.classList.contains('open')) {
            closeModal();
        }
    });

    // Alternar visibilidade do Preço Promocional
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

    // Validação antes do envio do formulário
    if (form) {
        form.addEventListener('submit', (e) => {
            const precoCusto = parseFloat(document.getElementById('preco_custo').value);
            const precoVenda = parseFloat(document.getElementById('preco_venda').value);
            const precoPromo = parseFloat(precoPromoInput.value);

            if (precoCusto <= 0 || precoVenda <= 0) {
                e.preventDefault();
                alert('Os preços de custo e venda devem ser maiores que zero.');
                return;
            }

            if (promoCheckbox.checked && (isNaN(precoPromo) || precoPromo >= precoVenda)) {
                e.preventDefault();
                alert('O preço promocional deve ser menor que o preço de venda normal.');
                return;
            }
        });
    }

    // Suporte a Drag & Drop para imagens
    if (PictureContainer) {
        PictureContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            PictureContainer.style.background = 'rgba(44, 155, 42, 0.1)';
        });

        PictureContainer.addEventListener('dragleave', () => {
            PictureContainer.style.background = '';
        });

        PictureContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            PictureContainer.style.background = '';
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                InputFile.files = files;
                const changeEvent = new Event('change', { bubbles: true });
                InputFile.dispatchEvent(changeEvent);
            }
        });
    }
});
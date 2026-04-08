const InputFile = document.querySelector("#inserir-imagem");
const PictureContainer = document.querySelector(".picture");

InputFile.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const pictureImage = document.querySelector('.picture-image');

    if (file) {
        const reader = new FileReader();
        
        reader.addEventListener('load', function(e) {
            // Remove imagem antiga se existir
            const oldImg = PictureContainer.querySelector('img');
            if (oldImg) oldImg.remove();

            // Cria a nova imagem
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = "100%";
            img.style.height = "100%";
            img.style.objectFit = "cover"; 

            // Esconde o texto "img" e adiciona a foto
            if (pictureImage) pictureImage.style.display = 'none';
            PictureContainer.appendChild(img);
            PictureContainer.style.border = "2px solid #000";
        });
        reader.readAsDataURL(file);
    }
});

// Modal functionality
const openModalBtn = document.getElementById('openModalBtn');
const modalBackdrop = document.getElementById('modalBackdrop');
const productModal = document.getElementById('productModal');
const modalClose = document.querySelector('.modal-close');
const form = document.querySelector('.modal form');



// Open modal
openModalBtn.addEventListener('click', () => {
    modalBackdrop.classList.add('open');
    productModal.classList.add('open');
    document.body.style.overflow = 'hidden'; // Prevent body scroll
});

// Close modal
function closeModal() {
    modalBackdrop.classList.remove('open');
    productModal.classList.remove('open');
    document.body.style.overflow = '';
}

// Close on X button
modalClose.addEventListener('click', closeModal);

// Close on backdrop click
modalBackdrop.addEventListener('click', (e) => {
    if (e.target === modalBackdrop) closeModal();
});

// Close on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && productModal.classList.contains('open')) {
        closeModal();
    }
});

// Função para abrir o modal e resetar os campos
openModalBtn.addEventListener('click', () => {
    form.reset(); // Limpa textos e números
    
    // Reset da área de imagem SEM usar innerHTML no pai
    const pictureImage = document.querySelector('.picture-image');
    const existingImg = PictureContainer.querySelector('img');
    
    if (existingImg) {
        existingImg.remove(); // Remove apenas a foto anterior
    }
    
    if (pictureImage) {
        pictureImage.style.display = 'block'; // Mostra o texto "img" novamente
    }

    PictureContainer.style.border = "2px dashed #aaa"; // Volta a borda original

    modalBackdrop.classList.add('open');
    productModal.classList.add('open');
    document.body.style.overflow = 'hidden';
});

// Basic form validation and submit
form.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    const nome = document.getElementById('nome').value.trim();
    const preco = document.getElementById('preco').value;
    
    if (!nome || !preco || !document.querySelector('#inserir-imagem').files[0]) {
        alert('Por favor, preencha todos os campos obrigatórios (nome, preço e imagem).');
        return;
    }
    
    if (parseFloat(preco) <= 0) {
        alert('O preço deve ser maior que zero.');
        return;
    }
    
    // For now, console log - replace with AJAX or let form submit
    console.log('Submitting product:', Object.fromEntries(formData));
    alert('Produto adicionado com sucesso! (Simulação - integre com backend)');
    closeModal();
    // form.submit(); // Uncomment for actual submit
});

// Drag & drop for image (enhancement)
const pictureLabel = document.querySelector('.picture');
pictureLabel.addEventListener('dragover', (e) => {
    e.preventDefault();
    pictureLabel.style.background = 'rgba(43,109,77,0.1)';
});

pictureLabel.addEventListener('dragleave', () => {
    pictureLabel.style.background = '';
});

pictureLabel.addEventListener('drop', (e) => {
    e.preventDefault();
    pictureLabel.style.background = '';
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.querySelector('#inserir-imagem').files = files;
        // Trigger change event for preview
        const changeEvent = new Event('change', { bubbles: true });
        document.querySelector('#inserir-imagem').dispatchEvent(changeEvent);
    }
});

const promoCheckbox = document.getElementById('promo');
const precoPromoContainer = document.getElementById('container-preco-promo');
const precoPromoInput = document.getElementById('preco_promo');

promoCheckbox.addEventListener('change', function() {
    if (this.checked) {
        // Se marcado, mostra o campo
        precoPromoContainer.style.display = 'block';
        precoPromoInput.setAttribute('required', 'true'); // Torna obrigatório se ativo
    } else {
        // Se desmarcado, esconde e limpa o valor
        precoPromoContainer.style.display = 'none';
        precoPromoInput.removeAttribute('required');
        precoPromoInput.value = ''; 
    }
});

// Resetar o campo quando o modal fechar ou abrir
openModalBtn.addEventListener('click', () => {
    // ... seu código de abertura existente ...
    precoPromoContainer.style.display = 'none';
});

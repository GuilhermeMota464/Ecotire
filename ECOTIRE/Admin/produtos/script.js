const InputFile = document.querySelector("#inserir-imagem");
const PictureContainer = document.querySelector(".picture"); // Pegamos a label principal
const PictureText = document.querySelector(".picture-image");

InputFile.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        
        reader.addEventListener('load', function(e) {
            // 1. Removemos o texto ou a imagem antiga
            PictureContainer.innerHTML = ""; 
            
            // 2. Criamos a nova imagem
            const img = document.createElement('img');
            img.src = e.target.result;
            
            // 3. Estilo direto para não ter erro de CSS
            img.style.width = "100%";
            img.style.height = "100%";
            img.style.objectFit = "cover"; 
            img.style.display = "block";

            PictureContainer.style.border = "2px solid #000000";

            // 4. Colocamos de volta o input (senão você não consegue trocar a foto de novo)
            PictureContainer.appendChild(InputFile);
            PictureContainer.appendChild(img);
        });
        reader.readAsDataURL(file);
    }
});
// Validação do formulário antes do envio
document.getElementById("cadastroForm").addEventListener("submit", function (event) {
    const email = document.getElementById("email").value;
    const domain = document.getElementById("domain").value;
    const fullEmail = email + domain;
    const senha = document.getElementById("senha").value;
    const errorDiv = document.getElementById("error");
    errorDiv.innerHTML = "";
    let errors = [];
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(fullEmail)) {
        errors.push("Email inválido.");
    }
    if (senha.length < 6) {
        errors.push("A senha deve ter pelo menos 6 caracteres.");
    }
    if (errors.length > 0) {
        event.preventDefault();
        errorDiv.innerHTML = errors.join("<br>");
    }
});

// Ajustar largura do select de domínio ao escolher "Outro..."
const domainSelect = document.getElementById("domain");

domainSelect.addEventListener("change", () => {
    if (domainSelect.value === "outro") {
        domainSelect.style.width = "10px";
        domainSelect.style.paddingLeft = "5px";
    } else {
        domainSelect.style.width = "420px"; // volta ao normal
    }
});

// Evitar que o usuário digite o domínio quando não for "Outro..."
const emailInput = document.getElementById("email");

emailInput.addEventListener("input", function () {
    if (domainSelect.value !== "outro") {
        this.value = this.value.replace(/@.*/, '');
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const teleInput = document.getElementById("tele");
    const domainSelect = document.getElementById("domain");
    const emailInput = document.getElementById("email");
    const cadastroForm = document.getElementById("cadastroForm");

    // Máscara dinâmica de telefone
    if (teleInput) {
        teleInput.addEventListener("input", function () {
            let value = this.value.replace(/\D/g, "");
            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 0) value = "(" + value;
            if (value.length > 3) value = value.slice(0, 3) + ") " + value.slice(3);
            if (value.length > 9) value = value.slice(0, 10) + "-" + value.slice(10);

            this.value = value;
        });
    }

    // Gerenciamento de input de e-mail vs seleção de domínio
    if (emailInput && domainSelect) {
        emailInput.addEventListener("input", function () {
            if (domainSelect.value !== "outro") {
                this.value = this.value.replace(/@.*/, '');
            }
        });

        domainSelect.addEventListener("change", function () {
            if (this.value === "outro") {
                emailInput.placeholder = "exemplo@dominio.com";
            } else {
                emailInput.placeholder = "Nome do usuário";
                emailInput.value = emailInput.value.replace(/@.*/, '');
            }
        });
    }

    // Validação antes do envio
    if (cadastroForm) {
        cadastroForm.addEventListener("submit", function (event) {
            const email = emailInput.value.trim();
            const domain = domainSelect.value;
            const fullEmail = (domain === "outro") ? email : email + domain;
            const senha = document.getElementById("senha").value;
            const telefone = teleInput.value;
            const errorDiv = document.getElementById("error");

            errorDiv.innerHTML = "";
            let errors = [];

            // Validação de Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(fullEmail)) {
                errors.push("Insira um endereço de e-mail válido.");
            }

            // Validação de Senha
            if (senha.length < 6) {
                errors.push("A senha deve ter no mínimo 6 caracteres.");
            }

            // Validação de Telefone
            const telefonePattern = /^\(\d{2}\) \d{4,5}-\d{4}$/;
            if (!telefonePattern.test(telefone)) {
                errors.push("Telefone inválido. Formato esperado: (XX) XXXXX-XXXX");
            }

            if (errors.length > 0) {
                event.preventDefault();
                errorDiv.innerHTML = errors.join("<br>");
            }
        });
    }
});
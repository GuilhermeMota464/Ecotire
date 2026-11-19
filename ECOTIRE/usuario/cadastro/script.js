document.addEventListener("DOMContentLoaded", function () {
    const teleInput = document.getElementById("tele");
    const domainSelect = document.getElementById("domain");
    const emailInput = document.getElementById("email");
    const cadastroForm = document.getElementById("cadastroForm");

    // Autoformatação de telefone: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX
    function formatarTelefone(valor) {
        const digits = valor.replace(/\D/g, "").slice(0, 11);

        if (digits.length === 0) return "";
        if (digits.length <= 2) return "(" + digits;
        if (digits.length <= 6) return "(" + digits.slice(0, 2) + ") " + digits.slice(2);
        if (digits.length <= 10) {
            return "(" + digits.slice(0, 2) + ") " + digits.slice(2, 6) + "-" + digits.slice(6);
        }
        return "(" + digits.slice(0, 2) + ") " + digits.slice(2, 7) + "-" + digits.slice(7, 11);
    }

    if (teleInput) {
        teleInput.addEventListener("input", function () {
            this.value = formatarTelefone(this.value);
        });
    }

    // Bloqueio de '@' quando um domínio pré-definido estiver selecionado
    if (emailInput && domainSelect) {
        emailInput.addEventListener("keydown", function (event) {
            if (event.key === "@") {
                if (domainSelect.value !== "outro") {
                    event.preventDefault();
                } else if (this.value.includes("@")) {
                    // Impede mais de um '@' mesmo no e-mail customizado
                    event.preventDefault();
                }
            }
        });

        emailInput.addEventListener("input", function () {
            if (domainSelect.value !== "outro") {
                // Se colar um e-mail completo, mantém apenas a parte antes do '@' e remove quaisquer '@'
                this.value = this.value.replace(/@.*/g, "");
            } else {
                // No modo customizado, permite apenas o primeiro '@'
                const partes = this.value.split("@");
                if (partes.length > 2) {
                    this.value = partes[0] + "@" + partes.slice(1).join("");
                }
            }
        });

        domainSelect.addEventListener("change", function () {
            if (this.value === "outro") {
                emailInput.placeholder = "exemplo@dominio.com";
            } else {
                emailInput.placeholder = "Seu usuário";
                emailInput.value = emailInput.value.replace(/@.*/g, "");
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
            const errors = [];

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(fullEmail)) {
                errors.push("Insira um endereço de e-mail válido.");
            }

            if (senha.length < 6) {
                errors.push("A senha deve ter no mínimo 6 caracteres.");
            }

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
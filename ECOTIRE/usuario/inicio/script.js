/* ==========================================================
   ARUANÃ — página inicial
   ========================================================== */

/* ---------- Menu hambúrguer (chamado pelo onclick do HTML) ---------- */
function toggleMenu(forcarFechar = false) {
    const menu = document.getElementById("menu-links");
    const botao = document.getElementById("icon");
    const icone = botao ? botao.querySelector("i") : null;
    if (!menu || !botao || !icone) return;

    const abrir = forcarFechar === true ? false : !menu.classList.contains("mostrar");

    menu.classList.toggle("mostrar", abrir);
    icone.classList.toggle("fa-xmark", abrir);
    icone.classList.toggle("fa-bars", !abrir);
    botao.setAttribute("aria-expanded", String(abrir));
    botao.setAttribute("aria-label", abrir ? "Fechar menu" : "Abrir menu");
}

(function () {
    "use strict";

    /* ---------- Menu: fecha ao escolher link, com Esc ou ao voltar ao desktop ---------- */
    document.querySelectorAll("#menu-links a").forEach((link) => {
        link.addEventListener("click", () => toggleMenu(true));
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") toggleMenu(true);
    });

    window.matchMedia("(min-width: 769px)").addEventListener("change", (e) => {
        if (e.matches) toggleMenu(true);
    });

    /* ---------- Carrossel ---------- */
    const banner = document.querySelector(".imgAlternada");
    const trilho = banner ? banner.querySelector(".Slide") : null;

    if (banner && trilho) {
        const slides = trilho.querySelectorAll("img");
        const total = slides.length;
        const dotsBox = document.getElementById("slide-dots");
        const INTERVALO = 6000;
        const reduzirMovimento = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        let atual = 0;
        let timer = null;
        const dots = [];

        if (dotsBox) {
            slides.forEach((_, i) => {
                const b = document.createElement("button");
                b.type = "button";
                b.setAttribute("aria-label", `Ir para o banner ${i + 1}`);
                b.addEventListener("click", () => { irPara(i); reiniciar(); });
                dotsBox.appendChild(b);
                dots.push(b);
            });
        }

        function irPara(indice) {
            atual = (indice + total) % total;
            trilho.style.transform = `translateX(-${atual * 100}%)`;
            dots.forEach((d, i) => {
                d.classList.toggle("ativo", i === atual);
                d.setAttribute("aria-current", i === atual ? "true" : "false");
            });
        }

        const proximo = () => irPara(atual + 1);
        const anterior = () => irPara(atual - 1);

        function parar() { clearInterval(timer); timer = null; }
        function iniciar() {
            if (reduzirMovimento || total < 2) return;
            parar();
            timer = setInterval(proximo, INTERVALO);
        }
        function reiniciar() { parar(); iniciar(); }

        banner.querySelector(".next").addEventListener("click", () => { proximo(); reiniciar(); });
        banner.querySelector(".prev").addEventListener("click", () => { anterior(); reiniciar(); });

        // Pausa enquanto o mouse ou o foco estiverem no banner
        banner.addEventListener("mouseenter", parar);
        banner.addEventListener("mouseleave", iniciar);
        banner.addEventListener("focusin", parar);
        banner.addEventListener("focusout", iniciar);

        // Arrastar com o dedo no celular
        let toqueX = null;
        banner.addEventListener("touchstart", (e) => { toqueX = e.touches[0].clientX; parar(); }, { passive: true });
        banner.addEventListener("touchend", (e) => {
            if (toqueX !== null) {
                const dx = e.changedTouches[0].clientX - toqueX;
                if (Math.abs(dx) > 40) (dx < 0 ? proximo : anterior)();
            }
            toqueX = null;
            iniciar();
        });

        // Setas do teclado quando o banner está focado
        banner.addEventListener("keydown", (e) => {
            if (e.key === "ArrowRight") { proximo(); reiniciar(); }
            if (e.key === "ArrowLeft") { anterior(); reiniciar(); }
        });

        irPara(0);
        iniciar();
    }

    /* ---------- Pesquisa ---------- */
    const ENDPOINT_BUSCA = "/ecotire/ecotire/funcoesPHP/busca.php";
    const inputBusca = document.getElementById("busca");
    const botaoBusca = document.getElementById("btn-busca");
    const divResultado = document.getElementById("resultado");

    if (inputBusca && divResultado) {
        let debounce = null;
        let controle = null;

        const fecharBusca = () => { divResultado.style.display = "none"; };

        async function buscar() {
            const termo = inputBusca.value.trim();
            if (termo.length < 2) { fecharBusca(); return; }

            if (controle) controle.abort();
            controle = new AbortController();

            try {
                const resp = await fetch(`${ENDPOINT_BUSCA}?busca=${encodeURIComponent(termo)}`, {
                    signal: controle.signal
                });
                if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                divResultado.innerHTML = await resp.text();
                divResultado.style.display = "block";
            } catch (erro) {
                if (erro.name !== "AbortError") fecharBusca();
            }
        }

        inputBusca.addEventListener("input", () => {
            clearTimeout(debounce);
            debounce = setTimeout(buscar, 250);
        });

        inputBusca.addEventListener("focus", () => {
            if (inputBusca.value.trim().length >= 2 && divResultado.innerHTML) {
                divResultado.style.display = "block";
            }
        });

        inputBusca.addEventListener("keydown", (e) => {
            if (e.key === "Escape") fecharBusca();
            if (e.key === "Enter") { e.preventDefault(); buscar(); }
        });

        if (botaoBusca) botaoBusca.addEventListener("click", () => { inputBusca.focus(); buscar(); });

        document.addEventListener("click", (e) => {
            if (!inputBusca.contains(e.target) && !divResultado.contains(e.target) &&
                !(botaoBusca && botaoBusca.contains(e.target))) {
                fecharBusca();
            }
        });
    }

    /* ---------- Formulário de contato ---------- */
    const form = document.getElementById("form-contato");

    if (form) {
        const telefone = document.getElementById("telefone");
        const status = document.getElementById("form-status");
        const enviar = document.getElementById("enviar_mensagem");

        // Máscara: (11) 2345-6789 ou (11) 92345-6789
        function formatarTelefone(valor) {
            const d = valor.replace(/\D/g, "").slice(0, 11);
            if (d.length === 0) return "";
            if (d.length <= 2) return `(${d}`;
            if (d.length <= 6) return `(${d.slice(0, 2)}) ${d.slice(2)}`;
            if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
            return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
        }

        if (telefone) {
            telefone.addEventListener("input", () => {
                telefone.value = formatarTelefone(telefone.value);
            });
        }

        function mostrarStatus(texto, tipo) {
            status.textContent = texto;
            status.className = tipo || "";
        }

        form.addEventListener("submit", async (e) => {
            mostrarStatus("");

            // Validação com mensagens em português
            const nome = form.nome;
            const msg = form.mensagem;
            const email = form.email;
            const digitos = telefone ? telefone.value.replace(/\D/g, "") : "";

            const erros = [];
            if (!nome.value.trim()) erros.push([nome, "Informe seu nome."]);
            if (digitos.length < 10) erros.push([telefone, "Informe um telefone com DDD."]);
            if (email.value && !email.checkValidity()) erros.push([email, "Esse e-mail parece incompleto."]);
            if (!msg.value.trim()) erros.push([msg, "Escreva sua mensagem."]);

            if (erros.length) {
                e.preventDefault();
                mostrarStatus(erros[0][1], "erro");
                erros[0][0].focus();
                return;
            }

            // Se houver um endpoint em data-endpoint, envia sem recarregar a página.
            // Sem endpoint, o formulário segue o envio padrão do navegador.
            const endpoint = form.dataset.endpoint;
            if (!endpoint) return;

            e.preventDefault();
            enviar.disabled = true;
            mostrarStatus("Enviando...");

            try {
                const resp = await fetch(endpoint, { method: "POST", body: new FormData(form) });
                if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                form.reset();
                mostrarStatus("Mensagem enviada. Obrigado pelo contato!", "ok");
            } catch (erro) {
                mostrarStatus("Não foi possível enviar agora. Tente novamente em instantes.", "erro");
            } finally {
                enviar.disabled = false;
            }
        });
    }

    /* ---------- Menu do usuário logado ---------- */
    const userMenu = document.getElementById("user-menu");
    const userToggle = document.getElementById("user-toggle");

    if (userMenu && userToggle) {
        const fecharUserMenu = () => {
            userMenu.classList.remove("aberto");
            userToggle.setAttribute("aria-expanded", "false");
        };

        userToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            const aberto = userMenu.classList.toggle("aberto");
            userToggle.setAttribute("aria-expanded", String(aberto));
        });

        document.addEventListener("click", (e) => {
            if (!userMenu.contains(e.target)) fecharUserMenu();
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") fecharUserMenu();
        });
    }

    /* ---------- Notificação (toast) ---------- */
    function mostrarToast(mensagem, icone = "fa-circle-check") {
        const toast = document.createElement("div");
        toast.className = "toast";
        toast.setAttribute("role", "status");
        toast.innerHTML = `<i class="fa-solid ${icone}" aria-hidden="true"></i><span></span>`;
        toast.querySelector("span").textContent = mensagem;
        document.body.appendChild(toast);

        requestAnimationFrame(() => toast.classList.add("toast-visivel"));

        setTimeout(() => {
            toast.classList.remove("toast-visivel");
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // Aviso gravado pela página de login (senha ou Google)
    if (sessionStorage.getItem("loginSucesso")) {
        sessionStorage.removeItem("loginSucesso");
        const nomeLogado = document.querySelector(".user-greeting-text strong");
        mostrarToast(nomeLogado
            ? `Login realizado! Bem-vindo, ${nomeLogado.textContent}.`
            : "Login realizado com sucesso!");
    }
})();
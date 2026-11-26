
/* ==========================================================
   ARUANÃ — página Sobre Nós
   ========================================================== */

/* ---------- Menu hambúrguer (chamado pelo onclick do HTML) ---------- */

function toggleMenu(forcarFechar = false) {
    const menu = document.getElementById("menu-links");
    const botao = document.getElementById("icon");
    const icone = botao ? botao.querySelector("i") : null;

    if (!menu || !botao || !icone) return;

    const abrir = forcarFechar === true
        ? false
        : !menu.classList.contains("mostrar");

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


    /* ======================================================
       MENU DO USUÁRIO LOGADO
       ====================================================== */

    const userMenu = document.getElementById("user-menu");
    const userToggle = document.getElementById("user-toggle");

    if (userMenu && userToggle) {

        function fecharUserMenu() {
            userMenu.classList.remove("aberto");
            userToggle.setAttribute("aria-expanded", "false");
        }

        userToggle.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const aberto = userMenu.classList.toggle("aberto");

            userToggle.setAttribute("aria-expanded", String(aberto));
        });

        document.addEventListener("click", (e) => {
            if (!userMenu.contains(e.target)) {
                fecharUserMenu();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && userMenu.classList.contains("aberto")) {
                fecharUserMenu();
                userToggle.focus();
            }
        });
    }


    /* ======================================================
       PESQUISA
       ====================================================== */

    const ENDPOINT_BUSCA = "/ecotire/ecotire/funcoesPHP/busca.php";

    const inputBusca = document.getElementById("busca");
    const botaoBusca = document.getElementById("btn-busca");
    const divResultado = document.getElementById("resultado");

    if (inputBusca && divResultado) {

        let debounce = null;
        let controle = null;

        const fecharBusca = () => {
            divResultado.style.display = "none";
        };

        async function buscar() {

            const termo = inputBusca.value.trim();

            if (termo.length < 2) {
                fecharBusca();
                return;
            }

            if (controle) controle.abort();

            controle = new AbortController();

            try {

                const resp = await fetch(
                    `${ENDPOINT_BUSCA}?busca=${encodeURIComponent(termo)}`,
                    {
                        signal: controle.signal
                    }
                );

                if (!resp.ok) {
                    throw new Error(`HTTP ${resp.status}`);
                }

                divResultado.innerHTML = await resp.text();
                divResultado.style.display = "block";

            } catch (erro) {

                if (erro.name !== "AbortError") {
                    fecharBusca();
                }

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

            if (e.key === "Escape") {
                fecharBusca();
            }

            if (e.key === "Enter") {
                e.preventDefault();
                buscar();
            }

        });

        if (botaoBusca) {
            botaoBusca.addEventListener("click", () => {
                inputBusca.focus();
                buscar();
            });
        }

        document.addEventListener("click", (e) => {

            if (
                !inputBusca.contains(e.target) &&
                !divResultado.contains(e.target) &&
                !(botaoBusca && botaoBusca.contains(e.target))
            ) {
                fecharBusca();
            }

        });
    }


    /* ======================================================
       CARRINHO: SÓ ABRE SE O USUÁRIO ESTIVER LOGADO
       ====================================================== */

    const botaoCarrinho = document.getElementById("botaoCarrinho");

    if (botaoCarrinho) {

        const irParaCarrinho = () => {
            window.location.href = "../carrinho/carrinho.php";
        };

        async function verificarCarrinho() {

            try {

                const resp = await fetch("../../funcoesPHP/verificarSessao.php");
                const dados = await resp.json();

                if (dados.logado) {
                    irParaCarrinho();
                    return;
                }

                // Sem o SweetAlert carregado, manda direto para o login
                if (typeof Swal === "undefined") {
                    window.location.href = "../login/login.php";
                    return;
                }

                const resultado = await Swal.fire({

                    title: "Acesso restrito",

                    text: "Você precisa fazer login para ver seu carrinho.",

                    icon: "warning",

                    showCancelButton: true,

                    confirmButtonColor: "#1e6b1d",

                    cancelButtonColor: "#8a8f7a",

                    confirmButtonText: "Fazer login",

                    cancelButtonText: "Depois"

                });

                if (resultado.isConfirmed) {
                    window.location.href = "../login/login.php";
                }

            } catch (erro) {

                // Se a verificação falhar, abre o carrinho
                // A própria página protege o acesso
                irParaCarrinho();

            }
        }

        botaoCarrinho.addEventListener("click", verificarCarrinho);

        botaoCarrinho.addEventListener("keydown", (e) => {

            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                verificarCarrinho();
            }

        });
    }

})();

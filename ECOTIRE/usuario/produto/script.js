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

function favoritarProduto(coracao) {
    coracao.classList.toggle("liked");

    if (coracao.classList.contains("liked")) {
        coracao.classList.remove("fa-regular");
        coracao.classList.add("fa-solid");
        coracao.style.color = "#e3262e";
    } else {
        coracao.classList.remove("fa-solid");
        coracao.classList.add("fa-regular");
        coracao.style.color = "";
    }
}

(function () {
    "use strict";

    /* ---------- Menu hambúrguer: fecha ao escolher link, Esc ou desktop ---------- */
    document.querySelectorAll("#menu-links a").forEach((link) => {
        link.addEventListener("click", () => toggleMenu(true));
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") toggleMenu(true);
    });

    window.matchMedia("(min-width: 769px)").addEventListener("change", (e) => {
        if (e.matches) toggleMenu(true);
    });

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

    /* ---------- Pesquisa ---------- */
    const inputBusca = document.getElementById("busca");
    const divResultado = document.getElementById("resultado");
    const iconeLupa = document.getElementById("lupa");
    const botaoBusca = document.getElementById("btn-busca");

    function fecharBusca() {
        if (divResultado) divResultado.style.display = "none";
        if (inputBusca) inputBusca.classList.remove("busca-ativa-input");
        if (iconeLupa) iconeLupa.classList.remove("busca-ativa-icone");
    }

    if (inputBusca && divResultado) {
        let debounce = null;
        let controle = null;

        async function buscar() {
            const query = inputBusca.value.trim();
            if (query.length < 2) {
                fecharBusca();
                return;
            }

            if (controle) controle.abort();
            controle = new AbortController();

            try {
                const response = await fetch(`/ecotire/ecotire/funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`, {
                    signal: controle.signal
                });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                divResultado.innerHTML = await response.text();
                divResultado.style.display = "block";
            } catch (erro) {
                if (erro.name !== "AbortError") fecharBusca();
            }
        }

        inputBusca.addEventListener("input", () => {
            clearTimeout(debounce);
            debounce = setTimeout(buscar, 250);
        });

        inputBusca.addEventListener("keydown", (e) => {
            if (e.key === "Escape") fecharBusca();
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
            if (!inputBusca.contains(e.target) && !divResultado.contains(e.target) &&
                !(botaoBusca && botaoBusca.contains(e.target))) {
                fecharBusca();
            }
        });
    }

    /* ---------- Verificação de sessão ao clicar no carrinho ---------- */
    const botaoCarrinho = document.getElementById("botaoCarrinho");
    if (botaoCarrinho) {
        botaoCarrinho.addEventListener("click", function (e) {
            e.preventDefault();
            fetch("../../funcoesPHP/verificarSessao.php")
                .then((response) => response.json())
                .then((data) => {
                    if (data.logado) {
                        window.location.href = "../carrinho/carrinho.php";
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({
                            title: "Acesso Restrito",
                            text: "Você precisa fazer login para ver seu carrinho.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#2c9b2a",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Fazer Login",
                            cancelButtonText: "Depois"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "../login/login.php";
                            }
                        });
                    } else {
                        window.location.href = "../login/login.php";
                    }
                })
                .catch((error) => {
                    console.error("Erro ao verificar sessão:", error);
                    window.location.href = "../carrinho/carrinho.php";
                });
        });
    }
})();
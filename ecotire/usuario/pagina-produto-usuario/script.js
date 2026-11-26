/* ARUANÃ — página de detalhe do produto */
(function () {
    "use strict";

    const compra = document.querySelector(".compra");
    if (!compra) return;

    const idProduto = compra.dataset.id;
    const estoque = Math.max(1, parseInt(compra.dataset.estoque, 10) || 1);
    const campo = document.getElementById("qtd");
    const btnMenos = document.getElementById("menos");
    const btnMais = document.getElementById("mais");
    const botao = document.getElementById("btnCarrinho");

    const limitar = (v) => {
        const num = parseInt(v, 10);
        if (isNaN(num) || num < 1) return 1;
        if (num > estoque) return estoque;
        return num;
    };

    const atualizarBotoes = (valor) => {
        btnMenos.disabled = valor <= 1;
        btnMais.disabled = valor >= estoque;
    };

    btnMenos.addEventListener("click", () => {
        const novo = limitar((parseInt(campo.value, 10) || 1) - 1);
        campo.value = novo;
        atualizarBotoes(novo);
    });

    btnMais.addEventListener("click", () => {
        const novo = limitar((parseInt(campo.value, 10) || 1) + 1);
        campo.value = novo;
        atualizarBotoes(novo);
    });

    campo.addEventListener("keydown", (e) => {
        if (["-", "+", "e", "E", ".", ","].includes(e.key)) {
            e.preventDefault();
        }
    });

    campo.addEventListener("input", () => {
        if (campo.value === "") return;
        const novo = limitar(campo.value);
        campo.value = novo;
        atualizarBotoes(novo);
    });

    campo.addEventListener("blur", () => {
        const novo = limitar(campo.value);
        campo.value = novo;
        atualizarBotoes(novo);
    });

    // Inicializa valor e estado dos botões
    campo.value = limitar(campo.value);
    atualizarBotoes(Number(campo.value));

    const alerta = (opcoes) =>
        typeof Swal !== "undefined" ? Swal.fire(opcoes) : Promise.resolve({ isConfirmed: false });

    botao.addEventListener("click", async () => {
        const quantidade = limitar(campo.value);
        campo.value = quantidade;
        botao.disabled = true;

        try {
            const corpo = new URLSearchParams({
                id_produto: idProduto,
                quantidade: quantidade
            });

            const resp = await fetch("../../funcoesPHP/addCarrinho.php", {
                method: "POST",
                body: corpo
            });

            const texto = await resp.text();
            let dados;
            try {
                dados = JSON.parse(texto);
            } catch (e) {
                console.error("Resposta inválida do PHP:", texto);
                throw new Error("Resposta inválida do servidor.");
            }

            if (dados.ok) {
                const textoSucesso = dados.mensagem || `${quantidade} ${quantidade === 1 ? "unidade adicionada" : "unidades adicionadas"} ao seu carrinho.`;

                await alerta({
                    title: "Adicionado ao carrinho!",
                    text: textoSucesso,
                    icon: "success",
                    confirmButtonText: "Continuar comprando",
                    confirmButtonColor: "#1e6b1d"
                });

                campo.value = 1;
                atualizarBotoes(1);
                return;
            }

            if (dados.logado === false) {
                const r = await alerta({
                    title: "Acesso restrito",
                    text: "Você precisa fazer login para adicionar ao carrinho.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1e6b1d",
                    cancelButtonColor: "#8a8f7a",
                    confirmButtonText: "Fazer login",
                    cancelButtonText: "Depois"
                });
                if (r.isConfirmed || typeof Swal === "undefined") {
                    window.location.href = "../login/login.php";
                }
                return;
            }

            await alerta({
                title: "Ops!",
                text: dados.mensagem || "Não foi possível adicionar ao carrinho.",
                icon: "error",
                confirmButtonColor: "#1e6b1d"
            });
        } catch (erro) {
            await alerta({
                title: "Erro de conexão",
                text: erro.message || "Tente novamente em instantes.",
                icon: "error",
                confirmButtonColor: "#1e6b1d"
            });
        } finally {
            botao.disabled = false;
        }
    });
})();
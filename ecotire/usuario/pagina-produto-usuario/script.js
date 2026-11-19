/* ARUANÃ — página de detalhe do produto */
(function () {
    "use strict";

    const compra = document.querySelector(".compra");
    if (!compra) return;

    const idProduto = compra.dataset.id;
    const estoque = Number(compra.dataset.estoque) || 1;
    const campo = document.getElementById("qtd");
    const botao = document.getElementById("btnCarrinho");

    const limitar = (v) => Math.min(Math.max(parseInt(v, 10) || 1, 1), estoque);

    document.getElementById("menos").addEventListener("click", () => {
        campo.value = limitar(Number(campo.value) - 1);
    });
    document.getElementById("mais").addEventListener("click", () => {
        campo.value = limitar(Number(campo.value) + 1);
    });
    campo.addEventListener("change", () => { campo.value = limitar(campo.value); });

    const alerta = (opcoes) =>
        typeof Swal !== "undefined" ? Swal.fire(opcoes) : Promise.resolve({ isConfirmed: false });

    botao.addEventListener("click", async () => {
        botao.disabled = true;

        try {
            const corpo = new URLSearchParams({
                id_produto: idProduto,
                quantidade: limitar(campo.value)
            });

            const resp = await fetch("../../funcoesPHP/adicionarCarrinho.php", {
                method: "POST",
                body: corpo
            });
            const dados = await resp.json();

            if (dados.ok) {
                window.location.href = "../carrinho/carrinho.php";
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
                text: "Tente novamente em instantes.",
                icon: "error",
                confirmButtonColor: "#1e6b1d"
            });
        } finally {
            botao.disabled = false;
        }
    });
})();
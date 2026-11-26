/* ARUANÃ — página de edição do produto (Admin) */
(function () {
    "use strict";

    const form = document.getElementById("formEdicao");
    if (!form) return;

    const precoVendaInput = document.getElementById("precoVendaFormatado");
    const precoPromoInput = document.getElementById("precoPromoFormatado");
    const precoVendaReal = document.getElementById("precoVendaReal");
    const precoPromoReal = document.getElementById("precoPromoReal");

    const togglePromo = document.getElementById("togglePromo");
    const boxPromo = document.getElementById("boxPromo");
    const seloDesconto = document.getElementById("seloDesconto");
    const avisoPromo = document.getElementById("avisoPromo");

    const campoEstoque = document.getElementById("estoque");
    const btnMenos = document.getElementById("menos");
    const btnMais = document.getElementById("mais");
    const btnSalvar = document.getElementById("btnSalvar");
    const btnResetar = document.getElementById("btnResetar");

    /* ---------- Formatação de moeda (BRL) ---------- */
    const formatarMoeda = (valorDigitado) => {
        const apenasDigitos = String(valorDigitado).replace(/\D/g, "");
        if (!apenasDigitos) return "";
        const numero = parseInt(apenasDigitos, 10) / 100;
        return numero.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const converterParaNumero = (textoFormatado) => {
        if (!textoFormatado) return 0;
        const limpo = textoFormatado.replace(/\./g, "").replace(",", ".");
        const num = parseFloat(limpo);
        return isNaN(num) ? 0 : num;
    };

    /* ---------- Promoção e selo % OFF ---------- */
    const atualizarValoresEPromo = () => {
        const venda = converterParaNumero(precoVendaInput.value);
        const promoAtiva = togglePromo.checked;
        const promo = promoAtiva ? converterParaNumero(precoPromoInput.value) : 0;

        precoVendaReal.value = venda.toFixed(2);
        precoPromoReal.value = promoAtiva && promo > 0 ? promo.toFixed(2) : "0";

        boxPromo.classList.toggle("oculto", !promoAtiva);
        precoPromoInput.required = promoAtiva;

        if (!promoAtiva || promo <= 0) {
            seloDesconto.style.display = "none";
            avisoPromo.textContent = "";
            return;
        }

        if (promo >= venda) {
            seloDesconto.style.display = "none";
            avisoPromo.textContent = "O preço promocional precisa ser menor que o preço de venda.";
            return;
        }

        const desconto = Math.round(((venda - promo) / venda) * 100);
        seloDesconto.textContent = `${desconto}% OFF`;
        seloDesconto.style.display = "inline-block";
        avisoPromo.textContent = "";
    };

    const aplicarMascaraPreco = (input) => {
        input.addEventListener("input", () => {
            input.value = formatarMoeda(input.value);
            atualizarValoresEPromo();
        });
    };

    aplicarMascaraPreco(precoVendaInput);
    aplicarMascaraPreco(precoPromoInput);

    togglePromo.addEventListener("change", () => {
        if (!togglePromo.checked) {
            precoPromoInput.value = "";
        }
        atualizarValoresEPromo();
    });

    /* ---------- Controle de Estoque ---------- */
    const limitarEstoque = (v) => {
        const num = parseInt(v, 10);
        if (isNaN(num) || num < 0) return 0;
        return num;
    };

    const atualizarBotoesEstoque = (valor) => {
        btnMenos.disabled = valor <= 0;
    };

    btnMenos.addEventListener("click", () => {
        const novo = limitarEstoque((parseInt(campoEstoque.value, 10) || 0) - 1);
        campoEstoque.value = novo;
        atualizarBotoesEstoque(novo);
    });

    btnMais.addEventListener("click", () => {
        const novo = limitarEstoque((parseInt(campoEstoque.value, 10) || 0) + 1);
        campoEstoque.value = novo;
        atualizarBotoesEstoque(novo);
    });

    campoEstoque.addEventListener("keydown", (e) => {
        if (["-", "+", "e", "E", ".", ","].includes(e.key)) {
            e.preventDefault();
        }
    });

    campoEstoque.addEventListener("input", () => {
        if (campoEstoque.value === "") return;
        const novo = limitarEstoque(campoEstoque.value);
        campoEstoque.value = novo;
        atualizarBotoesEstoque(novo);
    });

    campoEstoque.addEventListener("blur", () => {
        const novo = limitarEstoque(campoEstoque.value);
        campoEstoque.value = novo;
        atualizarBotoesEstoque(novo);
    });

    /* ---------- Envio via Fetch + Pop-up SweetAlert2 ---------- */
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        atualizarValoresEPromo();

        const venda = converterParaNumero(precoVendaInput.value);
        const promo = converterParaNumero(precoPromoInput.value);

        if (venda <= 0) {
            precoVendaInput.focus();
            return;
        }

        if (togglePromo.checked && (promo <= 0 || promo >= venda)) {
            avisoPromo.textContent = "Informe um valor promocional menor que o preço de venda.";
            precoPromoInput.focus();
            return;
        }

        btnSalvar.disabled = true;

        try {
            const resp = await fetch(form.action, {
                method: "POST",
                body: new FormData(form)
            });

            const texto = await resp.text();
            let dados;
            try {
                dados = JSON.parse(texto);
            } catch (erroParse) {
                console.error("Resposta inválida do PHP:", texto);
                throw new Error("Resposta inválida do servidor.");
            }

            if (dados.ok) {
                await Swal.fire({
                    title: "Salvo!",
                    text: dados.mensagem || "Produto atualizado com sucesso!",
                    icon: "success",
                    confirmButtonColor: "#1e6b1d"
                });
                return;
            }

            await Swal.fire({
                title: "Ops!",
                text: dados.mensagem || "Não foi possível salvar as alterações.",
                icon: "warning",
                confirmButtonColor: "#1e6b1d"
            });
        } catch (erro) {
            await Swal.fire({
                title: "Erro de conexão",
                text: erro.message || "Tente novamente em instantes.",
                icon: "error",
                confirmButtonColor: "#1e6b1d"
            });
        } finally {
            btnSalvar.disabled = false;
        }
    });

    btnResetar.addEventListener("click", () => {
        window.location.reload();
    });

    /* ---------- Inicialização ---------- */
    campoEstoque.value = limitarEstoque(campoEstoque.value);
    atualizarBotoesEstoque(Number(campoEstoque.value));
    atualizarValoresEPromo();
})();
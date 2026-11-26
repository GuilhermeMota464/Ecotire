/* ARUANÃ — página de edição do produto (Admin) */
(function () {
    "use strict";

    const form = document.getElementById("formEdicao");
    if (!form) return;

    const areaImagem = document.getElementById("areaImagem");
    const imgPrincipal = document.getElementById("produto-principal");
    const inputImagem = document.getElementById("inputImagem");
    const nomeArquivoImg = document.getElementById("nomeArquivoImg");
    const btnCancelarImg = document.getElementById("btnCancelarImg");
    let imagemOriginalSrc = imgPrincipal.src;
    const textoAjudaPadrao = nomeArquivoImg.textContent;

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

    /* ---------- Seleção e Pré-visualização da Imagem ---------- */
    const TIPOS_PERMITIDOS = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    const TAMANHO_MAX_BYTES = 5 * 1024 * 1024; // 5 MB

    const limparSelecaoImagem = () => {
        inputImagem.value = "";
        imgPrincipal.src = imagemOriginalSrc;
        nomeArquivoImg.textContent = textoAjudaPadrao;
        btnCancelarImg.classList.add("oculto");
    };

    const processarArquivoImagem = (arquivo) => {
        if (!arquivo) return;

        if (!TIPOS_PERMITIDOS.includes(arquivo.type)) {
            limparSelecaoImagem();
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Formato inválido",
                    text: "Envie uma imagem JPG, PNG, WEBP ou GIF.",
                    icon: "warning",
                    confirmButtonColor: "#1e6b1d"
                });
            }
            return;
        }

        if (arquivo.size > TAMANHO_MAX_BYTES) {
            limparSelecaoImagem();
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Arquivo muito grande",
                    text: "A imagem deve ter no máximo 5 MB.",
                    icon: "warning",
                    confirmButtonColor: "#1e6b1d"
                });
            }
            return;
        }

        const leitor = new FileReader();
        leitor.onload = (ev) => {
            imgPrincipal.src = ev.target.result;
            nomeArquivoImg.textContent = `Nova imagem selecionada: ${arquivo.name}`;
            btnCancelarImg.classList.remove("oculto");
        };
        leitor.readAsDataURL(arquivo);
    };

    areaImagem.addEventListener("click", (e) => {
        if (e.target.closest(".btn-trocar-imagem") || e.target === inputImagem) return;
        inputImagem.click();
    });

    inputImagem.addEventListener("change", () => {
        const arquivo = inputImagem.files && inputImagem.files[0];
        if (arquivo) {
            processarArquivoImagem(arquivo);
        }
    });

    btnCancelarImg.addEventListener("click", () => {
        limparSelecaoImagem();
    });

    // Suporte a arrastar e soltar (Drag & Drop)
    ["dragenter", "dragover"].forEach((evento) => {
        areaImagem.addEventListener(evento, (e) => {
            e.preventDefault();
            areaImagem.classList.add("arrastando");
        });
    });

    ["dragleave", "drop"].forEach((evento) => {
        areaImagem.addEventListener(evento, (e) => {
            e.preventDefault();
            areaImagem.classList.remove("arrastando");
        });
    });

    areaImagem.addEventListener("drop", (e) => {
        const arquivos = e.dataTransfer && e.dataTransfer.files;
        if (arquivos && arquivos.length > 0) {
            const dt = new DataTransfer();
            dt.items.add(arquivos[0]);
            inputImagem.files = dt.files;
            processarArquivoImagem(arquivos[0]);
        }
    });

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
            const urlDestino = form.dataset.action || "../../funcoesPHP/editionProduct.php";
            const resp = await fetch(urlDestino, {
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
                // Atualiza a referência da imagem original caso uma nova foto tenha sido salva
                if (inputImagem.files && inputImagem.files.length > 0) {
                    imagemOriginalSrc = imgPrincipal.src;
                    inputImagem.value = "";
                    nomeArquivoImg.textContent = textoAjudaPadrao;
                    btnCancelarImg.classList.add("oculto");
                }

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
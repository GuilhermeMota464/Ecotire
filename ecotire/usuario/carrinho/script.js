// Funções globais para osbotões com onclick inline

// Abrir modal de checkout
function abrirModal() {
    var modal = document.getElementById('modalCheckout');
    if (modal) {
        modal.classList.add('active');
        hiddenMessage();
    }
}

// Fechar modal de checkout
function fecharModal() {
    var modal = document.getElementById('modalCheckout');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Mostrar formulário de novo endereço
function mostrarFormEndereco() {
    var form = document.getElementById('formNovoEndereco');
    var btn = document.getElementById('btnAddEndereo');
    if (form) {
        form.style.display = 'block';
    }
    if (btn) {
        btn.style.display = 'none';
    }
}

// Salvar novo endereço
function salvarEndereco() {
    var cep = document.getElementById('novo_cep').value.trim();
    var numero = document.getElementById('novo_numero').value.trim();
    var complemento = document.getElementById('novo_complemento').value.trim();
    var message = document.getElementById('message');
    var btnSalvar = document.getElementById('btnSalvarEndereco');
    
    if (!cep || !numero) {
        showMsg('Preencha o CEP e o número.', 'error');
        return;
    }
    
    // Disable button during save
    if (btnSalvar) {
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = 'Salvando...';
    }
    
    // Make the fetch request
    fetch('../../funcoesPHP/adicionar_endereco.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cep: cep,
            numero: numero,
            complemento: complemento
        })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            showMsg('Endereço adicionado!', 'success');
            
            // Add new address to list
            var enderecosList = document.getElementById('enderecosList');
            if (enderecosList) {
                var novoEnderecoHtml = 
                    '<label class="endereco-option selected">' +
                        '<input type="radio" name="id_endereco" value="' + data.id_endereco + '" checked>' +
                        '<span class="check-icon"><i class="fa-solid fa-check-circle"></i></span>' +
                        '<div class="endereco-label">' +
                            '<span class="endereco-numero">Número: ' + data.numero + '</span><br>' +
                            '<span class="endereco-cep">CEP: ' + data.cep + '</span>' +
                            (data.complemento ? '<br><span>Complemento: ' + data.complemento + '</span>' : '') +
                        '</div>' +
                    '</label>';
                
                enderecosList.style.display = 'block';
                enderecosList.innerHTML = novoEnderecoHtml;
                
                // Add click handlers to options
                var options = document.querySelectorAll('.endereco-option');
                for (var i = 0; i < options.length; i++) {
                    options[i].addEventListener('click', function() {
                        var opts = document.querySelectorAll('.endereco-option');
                        for (var j = 0; j < opts.length; j++) {
                            opts[j].classList.remove('selected');
                        }
                        this.classList.add('selected');
                        this.querySelector('input').checked = true;
                    });
                }
            }
            
            // Hide the form
            var formNovo = document.getElementById('formNovoEndereco');
            var btnAdd = document.getElementById('btnAddEndereo');
            if (formNovo) formNovo.style.display = 'none';
            if (btnAdd) btnAdd.style.display = 'inline-block';
            
            // Hide "empty address" message
            var msgVazia = document.querySelector('.endereco-vazio-msg');
            if (msgVazia) msgVazia.style.display = 'none';
            
        } else {
            showMsg(data.message, 'error');
        }
    })
    .catch(function(error) {
        showMsg('Erro ao adicionar endereço. Tente novamente.', 'error');
        console.error('Erro:', error);
    })
    .finally(function() {
        if (btnSalvar) {
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = '<i class="fa-solid fa-check"></i> Salvar Endereço';
        }
    });
}

// Função para mostrar mensagens
function showMsg(text, type) {
    var message = document.getElementById('message');
    if (message) {
        message.textContent = text;
        message.className = 'message ' + type;
    }
}

// Função para ocultar mensagens
function hiddenMessage() {
    var message = document.getElementById('message');
    if (message) {
        message.className = 'message';
        message.style.display = 'none';
    }
}

// Inicialização quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle payment method selection
    var metodoBtns = document.querySelectorAll('.metodo-btn');
    for (var i = 0; i < metodoBtns.length; i++) {
        metodoBtns[i].addEventListener('click', function() {
            var btns = document.querySelectorAll('.metodo-btn');
            for (var j = 0; j < btns.length; j++) {
                btns[j].classList.remove('selected');
            }
            this.classList.add('selected');
            this.querySelector('input').checked = true;
        });
    }
    
    // Handle address selection
    var enderecoOptions = document.querySelectorAll('.endereco-option');
    for (var i = 0; i < enderecoOptions.length; i++) {
        enderecoOptions[i].addEventListener('click', function() {
            var opts = document.querySelectorAll('.endereco-option');
            for (var j = 0; j < opts.length; j++) {
                opts[j].classList.remove('selected');
            }
            this.classList.add('selected');
            this.querySelector('input').checked = true;
        });
    }
    
    // Handle cancel address button
    var btnCancelarEndereco = document.getElementById('btnCancelarEndereco');
    if (btnCancelarEndereco) {
        btnCancelarEndereco.addEventListener('click', function() {
            var formNovo = document.getElementById('formNovoEndereco');
            var btnAdd = document.getElementById('btnAddEndereo');
            if (formNovo) formNovo.style.display = 'none';
            if (btnAdd) btnAdd.style.display = 'inline-block';
            // Clear fields
            document.getElementById('novo_cep').value = '';
            document.getElementById('novo_numero').value = '';
            document.getElementById('novo_complemento').value = '';
        });
    }
    
    // Handle modal overlay click to close
    var modalCheckout = document.getElementById('modalCheckout');
    if (modalCheckout) {
        modalCheckout.addEventListener('click', function(e) {
            if (e.target === modalCheckout) {
                modalCheckout.classList.remove('active');
            }
        });
    }
    
    // Handle form submit
    var formCheckout = document.getElementById('formCheckout');
    if (formCheckout) {
        formCheckout.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate address selection
            var enderecoSelecionado = document.querySelector('input[name="id_endereco"]:checked');
            if (!enderecoSelecionado) {
                showMsg('Por favor, selecione um endereço de entrega.', 'error');
                return;
            }
            
            // Get data
            var id_endereco = enderecoSelecionado.value;
            var metodo_pagamento = document.querySelector('input[name="metodo_pagamento"]:checked').value;
            
            // Show loading
            var loading = document.getElementById('loading');
            var btnConfirmar = document.getElementById('btnConfirmar');
            if (loading) loading.classList.add('active');
            if (formCheckout) formCheckout.style.display = 'none';
            if (btnConfirmar) btnConfirmar.disabled = true;
            hiddenMessage();
            
            // Make request
            fetch('../checkout/finalizar_compra.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_endereco: id_endereco,
                    metodo_pagamento: metodo_pagamento
                })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (loading) loading.classList.remove('active');
                if (formCheckout) formCheckout.style.display = 'block';
                
                if (data.success) {
                    showMsg(data.message + ' Total: R$ ' + data.total, 'success');
                    
                    // Redirect after 2 seconds
                    setTimeout(function() {
                        window.location.href = '../inicio/index.php?pedido_sucesso=1';
                    }, 2000);
                } else {
                    showMsg(data.message, 'error');
                }
            })
            .catch(function(error) {
                showMsg('Erro ao processar pedido. Tente novamente.', 'error');
                console.error('Erro:', error);
                if (loading) loading.classList.remove('active');
                if (formCheckout) formCheckout.style.display = 'block';
            })
            .finally(function() {
                if (btnConfirmar) btnConfirmar.disabled = false;
            });
        });
    }
    
    // Check URL params for success message
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('pedido_sucesso') === '1') {
        console.log('Pedido realizado com sucesso!');
    }
});

// Aplica máscara de CEP no formato 00000-000
function applyCepMaskTo(input) {
    if (!input) return;
    // Set maxlength for formatted CEP: 00000-000 => 9 chars
    try { input.setAttribute('maxlength', '9'); } catch (e) {}
    try { input.setAttribute('inputmode', 'numeric'); } catch (e) {}
    input.addEventListener('input', function (e) {
        var v = e.target.value.replace(/\D/g, '');
        if (v.length > 8) v = v.slice(0,8);
        if (v.length <= 5) e.target.value = v;
        else e.target.value = v.slice(0,5) + '-' + v.slice(5,8);
    });
}

// Capitaliza primeiras letras de cada palavra (endereço/complemento)
function applyCapitalizeOnBlur(input) {
    if (!input) return;
    input.addEventListener('blur', function (e) {
        var s = e.target.value.trim().toLowerCase();
        e.target.value = s.replace(/\b\w/g, function(ch){ return ch.toUpperCase(); });
    });
}

// Inicializa máscaras em campos comuns quando este script estiver disponível
(function initMasksAndCartIcons() {
    // Phone inputs: any tel input or named telefone
    var phoneInputs = [].slice.call(document.querySelectorAll('input[type="tel"], input[name="telefone"], #telefone'));
    phoneInputs.forEach(function(inp){ applyPhoneMaskTo(inp); });

    // CEP fields (by common ids or name)
    var cepInputs = [].slice.call(document.querySelectorAll('input[name="cep"], input#novo_cep, input[name="cep_novo"]'));
    cepInputs.forEach(function(inp){ applyCepMaskTo(inp); });

    // Complement/address formatting
    var addrFields = [].slice.call(document.querySelectorAll('input[name="complemento"], input#novo_complemento, input[name="endereco"]'));
    addrFields.forEach(function(inp){ applyCapitalizeOnBlur(inp); });

    // Make any cart icon clickable to open carrinho (if not already a link)
    var icons = document.querySelectorAll('.fa-solid.fa-cart-shopping');
    icons.forEach(function(ic){
        if (ic.closest('a')) return;
        ic.style.cursor = 'pointer';
        ic.addEventListener('click', function(){
            window.location.href = '../carrinho/carrinho.php';
        });
    });
})();

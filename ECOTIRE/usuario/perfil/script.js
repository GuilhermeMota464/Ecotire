// Script para a página de perfil
document.addEventListener('DOMContentLoaded', function() {
    const TAB_STORAGE_KEY = 'ecotire.perfil.activeTab';

    // ================= ALERTA INTEGRADO NA PÁGINA =================
    function mostrarAlertaNaPagina(mensagem, tipo = 'erro') {
        const abaAtiva = document.querySelector('.tab-content.active') || document.querySelector('.main-content');
        if (!abaAtiva) return;

        // Remove alertas anteriores (tanto do PHP quanto do JS) para não acumular
        abaAtiva.querySelectorAll('.alert').forEach(el => el.remove());

        const alerta = document.createElement('div');
        alerta.className = `alert ${tipo} alert-js`;
        alerta.textContent = mensagem;

        const titulo = abaAtiva.querySelector('h2');
        if (titulo) {
            titulo.insertAdjacentElement('afterend', alerta);
        } else {
            abaAtiva.prepend(alerta);
        }

        alerta.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // ================= TROCA DE TABS =================
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Restaura tab ativa após refresh/submit
    const savedTab = window.localStorage.getItem(TAB_STORAGE_KEY);
    if (savedTab) {
        tabBtns.forEach(b => {
            if (b.getAttribute('data-tab') === savedTab) b.classList.add('active');
            else b.classList.remove('active');
        });

        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        const el = document.getElementById(savedTab);
        if (el) el.classList.add('active');
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const tabId = this.getAttribute('data-tab');
            if (tabId) window.localStorage.setItem(TAB_STORAGE_KEY, tabId);
            
            // Remove active class from all buttons
            tabBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Show the corresponding tab content
            document.getElementById(tabId).classList.add('active');
        });
    });

    // ================= VALIDAÇÃO DO FORMULÁRIO DE PERFIL =================
    const perfilForm = document.querySelector('.form-perfil');
    if (perfilForm) {
        perfilForm.addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const telefone = document.getElementById('telefone').value.trim();
            const digitos = telefone.replace(/\D/g, '');
            
            if (nome.length < 2) {
                e.preventDefault();
                mostrarAlertaNaPagina('Nome deve ter pelo menos 2 caracteres!');
                return false;
            }
            
            if (telefone !== '' && (digitos.length < 10 || digitos.length > 11)) {
                e.preventDefault();
                mostrarAlertaNaPagina('Telefone inválido! Use o formato (XX) XXXXX-XXXX');
                return false;
            }
            
            return true;
        });
    }
    
    // ================= VALIDAÇÃO DO FORMULÁRIO DE ENDEREÇO =================
    const enderecoForms = document.querySelectorAll('.form-endereco');
    enderecoForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const cep = this.querySelector('input[name="cep"]').value.trim();
            const numero = this.querySelector('input[name="numero"]').value.trim();
            
            // Valida CEP completo (5 dígitos + hífen opcional + 3 dígitos)
            const cepRegex = /^\d{5}-?\d{3}$/;
            if (!cepRegex.test(cep)) {
                e.preventDefault();
                mostrarAlertaNaPagina('CEP inválido! Use o formato XXXXX-XXX');
                return false;
            }
            
            if (numero === '' || Number(numero) < 1) {
                e.preventDefault();
                mostrarAlertaNaPagina('Número inválido!');
                return false;
            }
            
            return true;
        });
    });
    
    // ================= MÁSCARA PARA TELEFONE =================
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            const digits = e.target.value.replace(/\D/g, '').slice(0, 11);

            if (digits.length === 0) {
                e.target.value = '';
            } else if (digits.length <= 2) {
                e.target.value = '(' + digits;
            } else if (digits.length <= 6) {
                e.target.value = '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
            } else if (digits.length <= 10) {
                e.target.value = '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
            } else {
                e.target.value = '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7, 11);
            }
        });
    }
    
    // ================= MÁSCARA PARA CEP =================
    const cepInputs = document.querySelectorAll('input[name="cep"]');
    cepInputs.forEach(input => {
        input.maxLength = 9;
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').slice(0, 8);
            
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            
            e.target.value = value;
        });
    });
    
    // ================= ANIMAÇÃO DE CONFIRMAÇÃO =================
    const successAlert = document.querySelector('.alert.sucesso');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-10px)';
            successAlert.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                successAlert.remove();
            }, 500);
        }, 3000);
    }
});
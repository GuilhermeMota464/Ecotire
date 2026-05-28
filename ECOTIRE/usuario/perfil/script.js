// Script para a página de perfil
document.addEventListener('DOMContentLoaded', function() {
    const TAB_STORAGE_KEY = 'ecotire.perfil.activeTab';

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
            
            if (nome.length < 2) {
                e.preventDefault();
                alert('Nome deve ter pelo menos 2 caracteres!');
                return false;
            }
            
            if (telefone.length < 10) {
                e.preventDefault();
                alert('Telefone inválido!');
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
            
            // Simple CEP validation (5 digits + optional 3)
            const cepRegex = /^\d{5}-?\d{3}?$/;
            if (!cepRegex.test(cep)) {
                e.preventDefault();
                alert('CEP inválido! Use o formato XXXXX-XXX');
                return false;
            }
            
            if (numero < 1) {
                e.preventDefault();
                alert('Número inválido!');
                return false;
            }
            
            return true;
        });
    });
    
    // ================= MÁSCARA PARA TELEFONE =================
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 2) {
                    value = '(' + value;
                } else if (value.length <= 6) {
                    value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                } else {
                    value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7, 11);
                }
            }
            
            e.target.value = value;
        });
    }
    
    // ================= MÁSCARA PARA CEP =================
    const cepInputs = document.querySelectorAll('input[name="cep"]');
    cepInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 5) {
                    value = value;
                } else {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
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

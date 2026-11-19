
document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // CABEÇALHO — MESMO COMPORTAMENTO DA PÁGINA DE PRODUTOS
    // =====================================================

    const siteHeader = document.getElementById('siteHeader');
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.getElementById('mainNav');
    const profile = document.getElementById('profile');
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    // Sombra ao rolar a página
    if (siteHeader) {
        const onScroll = () => {
            siteHeader.classList.toggle('scrolled', window.scrollY > 4);
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // Menu mobile
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', () => {
            const aberto = mainNav.classList.toggle('open');

            menuToggle.setAttribute('aria-expanded', aberto);
            menuToggle.setAttribute(
                'aria-label',
                aberto ? 'Fechar menu' : 'Abrir menu'
            );
        });
    }

    // Menu do perfil
    function fecharPerfil() {
        if (!profileMenu || !profileBtn) return;

        profileMenu.classList.remove('open');
        profileBtn.setAttribute('aria-expanded', 'false');
    }

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();

            const aberto = profileMenu.classList.toggle('open');
            profileBtn.setAttribute('aria-expanded', aberto);
        });

        document.addEventListener('click', (e) => {
            if (profile && !profile.contains(e.target)) {
                fecharPerfil();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                fecharPerfil();
            }
        });
    }

    // =====================================================
    // EDIÇÃO DE USUÁRIOS
    // =====================================================

    function editarUsuario(id, nome, email, tipo) {

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_tipo').value = tipo;

        // A senha sempre começa vazia durante a edição
        document.getElementById('edit_senha').value = '';

        // Restaura o campo de senha para o tipo password
        const passInput = document.getElementById('edit_senha');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = toggleButton ? toggleButton.querySelector('i') : null;

        passInput.type = 'password';

        if (toggleIcon) {
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }

        if (toggleButton) {
            toggleButton.classList.remove('active');
            toggleButton.setAttribute('aria-label', 'Mostrar senha');
        }

        // Destaca a linha selecionada
        document.querySelectorAll('table tbody tr').forEach(linha => {
            linha.classList.remove('row-active');

            if (linha.dataset.id === String(id)) {
                linha.classList.add('row-active');
            }
        });

        // Rola até o formulário
        document.querySelector('.edit-form-container').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        document.getElementById('edit_nome').focus();
    }

    // Disponibiliza a função para outros usos
    window.editarUsuario = editarUsuario;

    // Botões de edição
    document.querySelectorAll('.edit-btn').forEach(botao => {
        botao.addEventListener('click', () => {
            const linha = botao.closest('tr');

            if (!linha) return;

            editarUsuario(
                linha.dataset.id,
                linha.dataset.nome,
                linha.dataset.email,
                linha.dataset.tipo
            );
        });
    });

    // =====================================================
    // LIMPAR FORMULÁRIO
    // =====================================================

    window.limparForm = function () {

        const form = document.getElementById('editUserForm');
        const passInput = document.getElementById('edit_senha');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = toggleButton ? toggleButton.querySelector('i') : null;

        form.reset();

        document.getElementById('edit_id').value = '';

        // Restaura o campo de senha
        passInput.type = 'password';

        if (toggleIcon) {
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }

        if (toggleButton) {
            toggleButton.classList.remove('active');
            toggleButton.setAttribute('aria-label', 'Mostrar senha');
        }

        // Remove o destaque das linhas
        document.querySelectorAll('table tbody tr').forEach(linha => {
            linha.classList.remove('row-active');
        });
    };

    // =====================================================
    // MOSTRAR / OCULTAR SENHA
    // =====================================================

    const togglePassword = document.getElementById('togglePassword');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {

            const passInput = document.getElementById('edit_senha');
            const icon = this.querySelector('i');

            const mostrar = passInput.type === 'password';

            passInput.type = mostrar ? 'text' : 'password';

            if (icon) {
                icon.classList.toggle('fa-eye', !mostrar);
                icon.classList.toggle('fa-eye-slash', mostrar);
            }

            this.classList.toggle('active', mostrar);

            this.setAttribute(
                'aria-label',
                mostrar ? 'Ocultar senha' : 'Mostrar senha'
            );
        });
    }

    // =====================================================
    // SELEÇÃO DAS LINHAS DA TABELA
    // =====================================================

    document.querySelectorAll('table tbody tr').forEach(linha => {

        linha.addEventListener('click', function (e) {

            // Não seleciona a linha ao clicar nos botões
            if (e.target.closest('button') || e.target.closest('form')) {
                return;
            }

            document.querySelectorAll('table tbody tr').forEach(outraLinha => {
                outraLinha.classList.remove('row-active');
            });

            this.classList.add('row-active');
        });

    });

});

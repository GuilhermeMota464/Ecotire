/* ---------- Mostrar / ocultar senha ---------- */
const toggleSenha = document.getElementById('toggle-senha');
const campoSenha = document.getElementById('password');

if (toggleSenha && campoSenha) {
    toggleSenha.addEventListener('click', () => {
        const mostrando = campoSenha.type === 'text';
        campoSenha.type = mostrando ? 'password' : 'text';
        toggleSenha.setAttribute('aria-label', mostrando ? 'Mostrar senha' : 'Ocultar senha');
        toggleSenha.innerHTML = `<i class="fa-solid ${mostrando ? 'fa-eye' : 'fa-eye-slash'}"></i>`;
    });
}

/* ---------- Envio do login ---------- */
const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, preencha todos os campos!',
                confirmButtonColor: '#2c9b2a'
            });
            return;
        }

        Swal.fire({
            title: 'Verificando dados...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData();
        formData.append('email', email);
        formData.append('senha', password);

        fetch('../../funcoesPHP/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Avisa a página inicial para mostrar a notificação de boas-vindas
                sessionStorage.setItem('loginSucesso', '1');

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Login realizado com sucesso! Redirecionando...',
                    showConfirmButton: false,
                    timer: 1800
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao entrar',
                    text: data.message,
                    confirmButtonColor: '#2c9b2a'
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro de conexão',
                text: 'Não foi possível conectar ao servidor.',
                confirmButtonColor: '#2c9b2a'
            });
        });
    });
}

/* ---------- Aviso após sair da conta (logout.php envia ?saiu=1) ---------- */
(function () {
    const params = new URLSearchParams(window.location.search);
    if (params.get('saiu') !== '1') return;

    if (window.Swal) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Você saiu da sua conta.',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    }

    params.delete('saiu');
    const qs = params.toString();
    history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : ''));
})();
const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, preencha todos os campos!',
                confirmButtonColor: '#2b8a3e'
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

        const formData = new FormData(loginForm);

        fetch('../../funcoesPHP/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) 
        .then(text => {
            const data = JSON.parse(text);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Login realizado com sucesso! Redirecionando...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = '../../usuario/inicio/index.php'; 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao entrar',
                    text: data.message,
                    confirmButtonColor: '#2b8a3e'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erro de conexão',
                text: 'Não foi possível conectar ao servidor.',
                confirmButtonColor: '#2b8a3e'
            });
        });
    });
}
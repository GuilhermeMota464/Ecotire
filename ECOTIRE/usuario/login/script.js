const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const terms = document.getElementById('terms').checked;

        if (!name || !email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, preencha todos os campos!',
                confirmButtonColor: '#2b8a3e'
            });
            return; 
        } 
        
        if (!terms) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Você precisa aceitar os Termos e Condições para continuar.',
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

        fetch('../funcoesPHP/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) 
        .then(text => {
            console.log("Resposta bruta do servidor:", text); // Olhe o console do navegador (F12)
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
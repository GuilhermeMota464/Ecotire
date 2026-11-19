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

        const formData = new FormData();
        formData.append('email', email);
        formData.append('senha', password);

        fetch('../../funcoesPHP/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Lê diretamente como JSON
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Login realizado com sucesso! Redirecionando...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Redireciona dinamicamente para o caminho retornado pelo servidor
                    window.location.href = data.redirect; 
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
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro de conexão',
                text: 'Não foi possível conectar ao servidor.',
                confirmButtonColor: '#2b8a3e'
            });
        });
    });
}
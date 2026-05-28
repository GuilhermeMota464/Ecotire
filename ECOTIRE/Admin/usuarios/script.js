function editarUsuario(id, nome, email, tipo) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_tipo').value = tipo;
    document.getElementById('edit_senha').value = ""; // Senha sempre limpa ao iniciar edição

    const todasLinhas = document.querySelectorAll('table tbody tr');
    todasLinhas.forEach(linha => {
        linha.classList.remove('row-active');
        if (linha.querySelector('td').innerText.includes(id)) {
            linha.classList.add('row-active');
        }
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
    document.getElementById('edit_nome').focus();
}

function limparForm() {
    const form = document.getElementById('editUserForm');
    const passInput = document.getElementById('edit_senha');
    const toggleIcon = document.getElementById('togglePassword');

    form.reset();
    document.getElementById('edit_id').value = '';
    
    // Reset do olho da senha
    passInput.setAttribute('type', 'password');
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
    toggleIcon.classList.remove('active');

    // Remove destaque da tabela
    document.querySelectorAll('table tbody tr').forEach(l => l.classList.remove('row-active'));
}

// Lógica do Olho
document.getElementById('togglePassword').addEventListener('click', function () {
    const passInput = document.getElementById('edit_senha');
    const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passInput.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
    this.classList.toggle('active');
});

// Clique na linha
document.querySelectorAll('table tbody tr').forEach(linha => {
    linha.addEventListener('click', function(e) {
        if (e.target.closest('button')) return; // Não ativa se clicar no botão
        document.querySelectorAll('table tbody tr').forEach(l => l.classList.remove('row-active'));
        this.classList.add('row-active');
    });
});
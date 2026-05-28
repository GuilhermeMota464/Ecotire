function favoritarProduto(coracao) {
    coracao.classList.toggle('liked');

    if (coracao.classList.contains('liked')) {
        coracao.classList.remove('fa-regular');
        coracao.classList.add('fa-solid');
        coracao.style.color = '#e3262e';
    } else {
        coracao.classList.remove('fa-solid');
        coracao.classList.add('fa-regular');
        coracao.style.color = '';
    }
}

//pesquisa
const inputBusca = document.getElementById('busca');
const divResultado = document.getElementById('resultado');

if (inputBusca && divResultado) {
    inputBusca.addEventListener('input', async () => {
        const query = inputBusca.value.trim();
        console.log(query);
        if (query.length < 2) {
            divResultado.style.display = 'none';
            return;
        }
        try {
            const response = await fetch(`/ecotire/ecotire/funcoesPHP/busca.php?busca=${encodeURIComponent(query)}`);
            const html = await response.text();
            console.log(html);
            divResultado.innerHTML = html;
            divResultado.style.display = 'block';
        } catch (erro) {
            console.log(erro);
        }
    });
}

function fecharBusca() {
    if (divResultado) divResultado.style.display = 'none';
    if (inputBusca) inputBusca.classList.remove('busca-ativa-input');
    if (iconeLupa) iconeLupa.classList.remove('busca-ativa-icone');
}

document.addEventListener('click', (e) => {
    if (inputBusca && !inputBusca.contains(e.target) && !divResultado.contains(e.target)) {
        fecharBusca();
    }
});

document.getElementById('botaoCarrinho').addEventListener('click', function() {
    fetch('../../funcoesPHP/verificarSessao.php')
    .then(response => response.json())
    .then(data => {
        if (data.logado) {
            window.location.href = '../carrinho/carrinho.php';
        } else {
            Swal.fire({
                title: 'Acesso Restrito',
                text: 'Você precisa fazer login para ver seu carrinho.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Fazer Login',
                cancelButtonText: 'Depois'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../carrinho/carrinho.php';
                }
            });
        }
    })
    .catch(error => {
        console.error('Erro ao verificar sessão:', error);
    });
});

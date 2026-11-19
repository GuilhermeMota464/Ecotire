<?php
session_start();
include '../../funcoesPHP/connection.php';
include '../../funcoesPHP/imagemWebp.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Busca os itens do carrinho
$sql = "SELECT c.*, p.nome, p.imagem,
               p.preco_venda,
               p.preco_promocional
        FROM carrinho c
        JOIN produtos p ON c.id_produto = p.id_produto
        WHERE c.id_usuario = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca os endereços do usuário
$sql_enderecos = "SELECT * FROM endereco WHERE id_usuario = ?";
$stmt_enderecos = $pdo->prepare($sql_enderecos);
$stmt_enderecos->execute([$id_usuario]);
$enderecos = $stmt_enderecos->fetchAll(PDO::FETCH_ASSOC);

// Calcula totais
$totalGeral = 0;
$totalItens = 0;
foreach ($produtos as $i => $produto) {
    $preco_unitario = $produto['preco_promocional'] !== null
        ? (float)$produto['preco_promocional']
        : (float)$produto['preco_venda'];
    $subtotal = $preco_unitario * (int)$produto['quantidade'];
    $produtos[$i]['_preco_unitario'] = $preco_unitario;
    $produtos[$i]['_subtotal'] = $subtotal;
    $totalGeral += $subtotal;
    $totalItens += (int)$produto['quantidade'];
}

function brl($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meu Carrinho — Ecotire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

    <!-- Navegação: link real (<a>) com texto visível, rótulo acessível e alvo de toque >= 44px -->
    <header class="topo">
        <a class="link-voltar" href="../inicio/index.php" aria-label="Voltar para a página inicial">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            <span>Voltar</span>
        </a>
    </header>

    <main class="container">
        <h1 class="titulo-pagina">
            <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
            Seu Carrinho
            <?php if (!empty($produtos)): ?>
                <span class="contador-itens"><?php echo $totalItens; ?> <?php echo $totalItens === 1 ? 'item' : 'itens'; ?></span>
            <?php endif; ?>
        </h1>

        <!-- Mensagens de sessão -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alerta alerta-sucesso" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <?php echo htmlspecialchars($_SESSION['sucesso']); ?>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alerta alerta-erro" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <?php echo htmlspecialchars($_SESSION['erro']); ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <?php if (empty($produtos)): ?>
            <section class="card carrinho-vazio">
                <div class="icone-vazio" aria-hidden="true"><i class="fa-solid fa-basket-shopping"></i></div>
                <h2>Seu carrinho está vazio</h2>
                <p>Que tal conhecer nossos produtos e escolher algo para você?</p>
                <a href="../produto/produto.php" class="btn-voltar">Ver produtos</a>
            </section>
        <?php else: ?>
            <div class="carrinho-layout">

                <section class="card tabela-wrapper" aria-label="Itens do carrinho">
                    <table class="tabela-carrinho">
                        <caption>Produtos no seu carrinho</caption>
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Qtd</th>
                                <th scope="col">Preço unit.</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col"><span class="sr-only">Ação</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto): ?>
                                <?php $nome = htmlspecialchars($produto['nome']); ?>
                                <tr>
                                    <td class="celula-produto">
                                        <div class="produto-info">
                                            <?php $imagemSrc = imagemParaDataUri($produto['imagem']); ?>
                                            <?php if ($imagemSrc): ?>
                                                <img src="<?php echo htmlspecialchars($imagemSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                                    alt="<?php echo $nome; ?>" width="72" height="72" loading="lazy">
                                            <?php else: ?>
                                                <div class="produto-sem-imagem" aria-hidden="true">
                                                    <i class="fa-solid fa-leaf"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span><?php echo $nome; ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Quantidade"><span class="qtd-pill"><?php echo (int)$produto['quantidade']; ?></span></td>
                                    <td data-label="Preço unitário">
                                        <span>
                                            <?php if ($produto['preco_promocional'] !== null): ?>
                                                <span class="preco-antigo"><?php echo brl((float)$produto['preco_venda']); ?></span>
                                            <?php endif; ?>
                                            <span class="preco-atual"><?php echo brl($produto['_preco_unitario']); ?></span>
                                        </span>
                                    </td>
                                    <td data-label="Subtotal" class="subtotal"><?php echo brl($produto['_subtotal']); ?></td>
                                    <td data-label="Ação">
                                        <a href="../../funcoesPHP/removeCarrinho.php?id_item=<?php echo (int)$produto['id_item']; ?>"
                                           class="btn-remover"
                                           aria-label="Remover <?php echo $nome; ?> do carrinho">
                                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Remover
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <aside class="card resumo" aria-label="Resumo do pedido">
                    <h2>Resumo do pedido</h2>
                    <div class="resumo-linha">
                        <span>Itens (<?php echo $totalItens; ?>)</span>
                        <span><?php echo brl($totalGeral); ?></span>
                    </div>
                    <div class="resumo-total">
                        <span>Total</span>
                        <strong><?php echo brl($totalGeral); ?></strong>
                    </div>
                    <button type="button" class="btn-finalizar" id="btnAbrirCheckout"
                            aria-haspopup="dialog" aria-controls="modalCheckout">
                        Finalizar compra
                    </button>
                    <p class="resumo-seguro"><i class="fa-solid fa-lock" aria-hidden="true"></i> Compra segura</p>
                </aside>

            </div>
        <?php endif; ?>
    </main>

    <!-- Modal de Checkout -->
    <div class="modal-overlay" id="modalCheckout" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="tituloCheckout">
            <h2 id="tituloCheckout"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> Finalizar compra</h2>

            <p class="erro-checkout hidden" id="erroCheckout" role="alert"></p>

            <!-- Endereço -->
            <fieldset class="grupo">
                <legend>Endereço de entrega</legend>

                <div id="listaEnderecos">
                    <?php if (!empty($enderecos)): ?>
                        <?php foreach ($enderecos as $endereco): ?>
                            <label class="opcao">
                                <input type="radio" name="id_endereco" class="sr-only"
                                       value="<?php echo (int)$endereco['id_endereco']; ?>">
                                <span class="titulo">Número: <?php echo htmlspecialchars($endereco['numero']); ?></span>
                                <span class="detalhe">CEP: <?php echo htmlspecialchars($endereco['cep']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="sem-endereco">Nenhum endereço cadastrado.</p>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn-add-endereco" id="btnNovoEndereco"
                        aria-expanded="false" aria-controls="formNovoEndereco">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Adicionar novo endereço
                </button>

                <div id="formNovoEndereco" class="novo-endereco hidden">
                    <h3>Novo endereço</h3>
                    <form method="POST" action="../../funcoesPHP/adicionar_endereco_simples.php">
                        <div class="campo">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" placeholder="00000-000"
                                   inputmode="numeric" autocomplete="postal-code" required>
                        </div>
                        <div class="campo">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" placeholder="Número"
                                   inputmode="numeric" pattern="[0-9]*" autocomplete="off" required>
                        </div>
                        <div class="campo">
                            <label for="complemento">Complemento (opcional)</label>
                            <input type="text" id="complemento" name="complemento"
                                   placeholder="Apartamento, bloco, etc." autocomplete="address-line2">
                        </div>
                        <button type="submit" class="btn-confirmar">Salvar endereço</button>
                        <button type="button" class="btn-cancelar" id="btnCancelarNovo">Cancelar</button>
                    </form>
                </div>
            </fieldset>

            <!-- Pagamento -->
            <fieldset class="grupo">
                <legend>Método de pagamento</legend>
                <div class="metodo-pagamento">
                    <label class="opcao">
                        <input type="radio" name="metodo_pagamento" value="pix" class="sr-only" checked>
                        <i class="fa-solid fa-wallet" aria-hidden="true"></i> PIX
                    </label>
                    <label class="opcao">
                        <input type="radio" name="metodo_pagamento" value="cartao" class="sr-only">
                        <i class="fa-solid fa-credit-card" aria-hidden="true"></i> Cartão
                    </label>
                    <label class="opcao">
                        <input type="radio" name="metodo_pagamento" value="boleto" class="sr-only">
                        <i class="fa-solid fa-barcode" aria-hidden="true"></i> Boleto
                    </label>
                </div>
            </fieldset>

            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" id="btnFecharCheckout">Cancelar</button>
                <button type="button" class="btn-confirmar" id="btnConfirmarCompra">Confirmar compra</button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var overlay   = document.getElementById('modalCheckout');
        var btnAbrir  = document.getElementById('btnAbrirCheckout');
        var btnFechar = document.getElementById('btnFecharCheckout');
        var btnNovo   = document.getElementById('btnNovoEndereco');
        var btnCancelarNovo = document.getElementById('btnCancelarNovo');
        var formNovo  = document.getElementById('formNovoEndereco');
        var erroBox   = document.getElementById('erroCheckout');
        var ultimoFoco = null;

        var FOCAVEIS = 'a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])';

        function mostrarErro(msg) {
            erroBox.textContent = msg;
            erroBox.classList.remove('hidden');
        }

        function limparErro() {
            erroBox.textContent = '';
            erroBox.classList.add('hidden');
        }

        function abrirModal() {
            ultimoFoco = document.activeElement;
            overlay.hidden = false;
            document.body.classList.add('modal-aberto');
            limparErro();
            var primeiro = overlay.querySelector('input[name="id_endereco"], #btnNovoEndereco');
            (primeiro || overlay.querySelector(FOCAVEIS)).focus();
        }

        function fecharModal() {
            overlay.hidden = true;
            document.body.classList.remove('modal-aberto');
            if (ultimoFoco) ultimoFoco.focus();
        }

        function alternarFormEndereco(mostrar) {
            formNovo.classList.toggle('hidden', !mostrar);
            btnNovo.classList.toggle('hidden', mostrar);
            btnNovo.setAttribute('aria-expanded', mostrar ? 'true' : 'false');
            if (mostrar) {
                document.getElementById('cep').focus();
            } else {
                btnNovo.focus();
            }
        }

        if (btnAbrir) btnAbrir.addEventListener('click', abrirModal);
        btnFechar.addEventListener('click', fecharModal);
        btnNovo.addEventListener('click', function () { alternarFormEndereco(true); });
        btnCancelarNovo.addEventListener('click', function () { alternarFormEndereco(false); });

        // Fecha ao clicar fora da caixa
        overlay.addEventListener('mousedown', function (e) {
            if (e.target === overlay) fecharModal();
        });

        // Esc fecha; Tab fica preso dentro do modal
        document.addEventListener('keydown', function (e) {
            if (overlay.hidden) return;
            if (e.key === 'Escape') {
                fecharModal();
                return;
            }
            if (e.key === 'Tab') {
                var itens = Array.prototype.filter.call(
                    overlay.querySelectorAll(FOCAVEIS),
                    function (el) { return el.offsetParent !== null || el.classList.contains('sr-only'); }
                );
                if (!itens.length) return;
                var primeiro = itens[0], ultimo = itens[itens.length - 1];
                if (e.shiftKey && document.activeElement === primeiro) {
                    e.preventDefault(); ultimo.focus();
                } else if (!e.shiftKey && document.activeElement === ultimo) {
                    e.preventDefault(); primeiro.focus();
                }
            }
        });

        // Envia o pedido
        document.getElementById('btnConfirmarCompra').addEventListener('click', function () {
            var endereco = document.querySelector('input[name="id_endereco"]:checked');
            if (!endereco) {
                mostrarErro('Por favor, selecione um endereço de entrega.');
                return;
            }
            var pagamento = document.querySelector('input[name="metodo_pagamento"]:checked');

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '../checkout/finalizar_compra.php';

            [['id_endereco', endereco.value], ['metodo_pagamento', pagamento ? pagamento.value : 'pix']]
                .forEach(function (par) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = par[0];
                    input.value = par[1];
                    form.appendChild(input);
                });

            document.body.appendChild(form);
            form.submit();
        });
    })();
    </script>
</body>
</html>
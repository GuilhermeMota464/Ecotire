<?php
session_start();
include '../../funcoesPHP/connection.php';

if (!isset($_SESSION['id_usuario'])) {
    header ("Location: ../login/login.php");
    exit();
};

$id_usuario = $_SESSION['id_usuario'] ?? null;

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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Meu Carrinho</title>
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .modal h2 {
            color: var(--cor-primaria);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--fundo);
            padding-bottom: 10px;
        }
        
        .endereco-option {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
        }
        
        .endereco-option:hover {
            border-color: var(--cor-primaria);
        }
        
        .endereco-option.selected {
            border-color: var(--cor-primaria);
            background: rgba(43, 109, 77, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }
        
        .metodo-pagamento {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .metodo-btn {
            flex: 1;
            min-width: 80px;
            padding: 15px 10px;
            border: 2px solid #eee;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        
        .metodo-btn:hover {
            border-color: var(--cor-primaria);
        }
        
        .metodo-btn.selected {
            border-color: var(--cor-primaria);
            background: var(--cor-primaria);
            color: white;
        }
        
        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-cancelar, .btn-confirmar, .btn-add-endereco {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        
        .btn-cancelar {
            border: 2px solid #ddd;
            background: white;
            color: #666;
        }
        
        .btn-confirmar, .btn-add-endereco {
            border: none;
            background: var(--cor-primaria);
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-add-endereco {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        .hidden {
            display: none;
        }
        
        .new-address-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .new-address-box h4 {
            color: var(--cor-primaria);
            margin-bottom: 15px;
        }
        
        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .btn-row .btn-cancelar, .btn-row .btn-confirmar {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seu Carrinho</h1>
        
        <!-- Mensagens de sessão -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['sucesso']); ?>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['erro'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <i class="fa-solid fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['erro']); ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        
        <?php if (empty($produtos)): ?>
            <div class="carrinho-vazio">
                <i class="fa-solid fa-cart-shopping" style="font-size: 50px; color: #ccc;"></i>
                <p>Seu carrinho está vazio.</p>
                <a href="../inicio/index.php" class="btn-voltar">Ver Produtos</a>
            </div>
        <?php else: ?>
            <table class="tabela-carrinho">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço Unit.</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalGeral = 0;
                    foreach ($produtos as $produto): 
                        $preco_unitario = $produto['preco_promocional'] !== null ? (float)$produto['preco_promocional'] : (float)$produto['preco_venda'];
                        $subtotal = $preco_unitario * (int)$produto['quantidade'];
                        $totalGeral += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="../produto/Assets/<?php echo $produto['imagem'] . '_1'; ?>.webp" width="50">
                            <?php echo htmlspecialchars($produto['nome']); ?>
                        </td>
                        <td><?php echo $produto['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($produto['preco_promocional'] !== null ? $produto['preco_promocional'] : $produto['preco_venda'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        <td>
                            <a href="../../funcoesPHP/removeCarrinho.php?id_item=<?php echo $produto['id_item']; ?>" class="btn-remover">
                                Remover
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="resumo">
                <h3>Total: R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></h3>
                <button type="button" class="btn-finalizar" onclick="document.getElementById('modalCheckout').style.display='flex'">Finalizar Compra</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Checkout -->
    <div class="modal-overlay" id="modalCheckout">
        <div class="modal">
            <h2><i class="fa-solid fa-bag-shopping"></i> Finalizar Compra</h2>
            
            <!-- Seção de Endereço -->
            <div class="form-group">
                <label>Endereço de Entrega</label>
                
                <!-- Botão para adicionar novo endereço -->
                <button type="button" class="btn-add-endereco" id="btnAddNewAddress" onclick="showNewAddressForm()">
                    <i class="fa-solid fa-plus"></i> Adicionar Novo Endereço
                </button>
                
                <!-- Formulário para novo endereço -->
                <div id="newAddressForm" class="new-address-box hidden">
                    <h4>Novo Endereço</h4>
                    <form method="POST" action="../../funcoesPHP/adicionar_endereco_simples.php">
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" name="cep" placeholder="00000-000" required>
                        </div>
                        <div class="form-group">
                            <label>Número</label>
                            <input type="number" name="numero" placeholder="Número" required>
                        </div>
                        <div class="form-group">
                            <label>Complemento (opcional)</label>
                            <input type="text" name="complemento" placeholder="Apartamento, bloco, etc.">
                        </div>
                        <button type="submit" class="btn-confirmar" style="width:100%;">Salvar Endereço</button>
                        <button type="button" class="btn-cancelar" style="width:100%; margin-top:10px;" onclick="hideNewAddressForm()">Cancelar</button>
                    </form>
                </div>
                
                <!-- Lista de endereços existentes -->
                <div id="addressesList" style="margin-top: 15px;">
                    <?php if (!empty($enderecos)): ?>
                        <?php foreach ($enderecos as $endereco): ?>
                            <div class="endereco-option" onclick="selectAddress(<?php echo $endereco['id_endereco']; ?>, this)">
                                <input type="radio" name="id_endereco" value="<?php echo $endereco['id_endereco']; ?>" style="display:none;">
                                <span style="font-weight:600;">Número: <?php echo htmlspecialchars($endereco['numero']); ?></span>
                                <br>
                                <span style="color:#666;">CEP: <?php echo htmlspecialchars($endereco['cep']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #666;">Nenhum endereço cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Seção de Pagamento -->
            <div class="form-group">
                <label>Método de Pagamento</label>
                <div class="metodo-pagamento" id="paymentMethods">
                    <div class="metodo-btn selected" onclick="selectPayment('pix', this)">
                        <input type="radio" name="metodo_pagamento" value="pix" checked style="display:none;">
                        <i class="fa-solid fa-wallet" style="font-size: 24px;"></i><br>PIX
                    </div>
                    <div class="metodo-btn" onclick="selectPayment('cartao', this)">
                        <input type="radio" name="metodo_pagamento" value="cartao" style="display:none;">
                        <i class="fa-solid fa-credit-card" style="font-size: 24px;"></i><br>Cartão
                    </div>
                    <div class="metodo-btn" onclick="selectPayment('boleto', this)">
                        <input type="radio" name="metodo_pagamento" value="boleto" style="display:none;">
                        <i class="fa-solid fa-barcode" style="font-size: 24px;"></i><br>Boleto
                    </div>
                </div>
            </div>
            
            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" onclick="document.getElementById('modalCheckout').style.display='none'">Cancelar</button>
                <button type="button" class="btn-confirmar" onclick="submitCheckout()">Confirmar Compra</button>
            </div>
        </div>
    </div>
    
    <script>
        var selectedAddressId = null;
        
        function showNewAddressForm() {
            document.getElementById('newAddressForm').classList.remove('hidden');
            document.getElementById('btnAddNewAddress').classList.add('hidden');
        }
        
        function hideNewAddressForm() {
            document.getElementById('newAddressForm').classList.add('hidden');
            document.getElementById('btnAddNewAddress').classList.remove('hidden');
        }
        
        function selectAddress(id, element) {
            // Remove selected from all options
            var options = document.querySelectorAll('.endereco-option');
            options.forEach(function(opt) {
                opt.classList.remove('selected');
            });
            
            // Add selected to clicked
            element.classList.add('selected');
            selectedAddressId = id;
        }
        
        function selectPayment(method, element) {
            // Remove selected from all options
            var options = document.querySelectorAll('.metodo-btn');
            options.forEach(function(opt) {
                opt.classList.remove('selected');
            });
            
            // Add selected to clicked
            element.classList.add('selected');
        }
        
        function submitCheckout() {
            // Check if address is selected
            if (!selectedAddressId) {
                alert('Por favor, selecione um endereço de entrega.');
                return;
            }
            
            // Get selected payment method
            var paymentRadios = document.getElementsByName('metodo_pagamento');
            var paymentMethod = 'pix';
            for (var i = 0; i < paymentRadios.length; i++) {
                if (paymentRadios[i].checked) {
                    paymentMethod = paymentRadios[i].value;
                    break;
                }
            }
            
            // Create and submit form
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '../checkout/finalizar_compra.php';
            
            var addressInput = document.createElement('input');
            addressInput.type = 'hidden';
            addressInput.name = 'id_endereco';
            addressInput.value = selectedAddressId;
            
            var paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'metodo_pagamento';
            paymentInput.value = paymentMethod;
            
            form.appendChild(addressInput);
            form.appendChild(paymentInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

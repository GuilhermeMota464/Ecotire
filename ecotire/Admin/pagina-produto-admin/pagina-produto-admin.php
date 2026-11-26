<?php
require_once __DIR__ . '/../auth_admin/auth_admin.php';
include '../../funcoesPHP/connection.php';

if (isset($_GET['id'])) {
    $id =$_GET['id'];

    $stmt =$pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
    $stmt->execute([$id]);
    $produto =$stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "Produto não encontrado.";
    exit;
}

$preco_venda = (float) ($produto['preco_venda'] ?? 0);
$preco_promocional = (float) ($produto['preco_promocional'] ?? 0);
$tem_promo =$preco_promocional > 0 && $preco_promocional <$preco_venda;
$estoque = (int) ($produto['estoque'] ?? 0);

$src_imagem = '../../assetsGerais/ecotire.webp';
if (!empty($produto['imagem'])) {
    $src_imagem = 'data:image/jpeg;base64,' . base64_encode($produto['imagem']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Aruanã</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

<main class="pagina-admin">
    <a href="../produtos/produtos-admin.php" class="btn-voltar">
        <i class="fa-solid fa-arrow-left"></i> Voltar para Produtos
    </a>

    <form action="../../funcoesPHP/editionProduct.php" method="POST" id="formEdicao" class="detalhe">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="hidden" name="preco_venda" id="precoVendaReal" value="<?php echo number_format($preco_venda, 2, '.', ''); ?>">
        <input type="hidden" name="preco_promocional" id="precoPromoReal" value="<?php echo $tem_promo ? number_format($preco_promocional, 2, '.', '') : '0'; ?>">

        <!-- Coluna da Imagem -->
        <div class="detalhe-imagem">
            <img src="<?php echo $src_imagem; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="foto-produto" id="produto-principal">
            <span class="selo-desconto" id="seloDesconto" style="<?php echo $tem_promo ? '' : 'display: none;'; ?>">0% OFF</span>
        </div>

        <!-- Coluna das Informações / Edição -->
        <div class="detalhe-info">
            <input type="text" name="modelo" class="input-edit modelo" value="<?php echo htmlspecialchars($produto['modelo'] ?? ''); ?>" placeholder="MODELO DO PRODUTO" required>

            <textarea name="nome" class="input-edit nome" rows="2" placeholder="Nome do produto" required><?php echo htmlspecialchars($produto['nome'] ?? ''); ?></textarea>

            <!-- Área de Preços -->
            <div class="precos-detalhe">
                <div class="campo-preco">
                    <label for="precoVendaFormatado">Preço de venda</label>
                    <div class="input-moeda">
                        <span>R$</span>
                        <input type="text" id="precoVendaFormatado" class="preco-input" value="<?php echo number_format($preco_venda, 2, ',', '.'); ?>" inputmode="numeric" required>
                    </div>
                </div>

                <div class="promo-controle">
                    <label class="switch-label" for="togglePromo">
                        <span>Promoção ativa</span>
                        <span class="switch">
                            <input type="checkbox" name="promo" id="togglePromo" value="1" <?php echo $tem_promo ? 'checked' : ''; ?>>
                            <span class="slider"></span>
                        </span>
                    </label>
                </div>

                <div class="campo-preco campo-promo <?php echo $tem_promo ? '' : 'oculto'; ?>" id="boxPromo">
                    <label for="precoPromoFormatado">Preço promocional</label>
                    <div class="input-moeda">
                        <span>R$</span>
                        <input type="text" id="precoPromoFormatado" class="preco-input" value="<?php echo $tem_promo ? number_format($preco_promocional, 2, ',', '.') : ''; ?>" inputmode="numeric" placeholder="0,00">
                    </div>
                    <small class="aviso-promo" id="avisoPromo"></small>
                </div>
            </div>

            <!-- Descrição -->
            <div class="campo-grupo">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" class="input-edit descricao" rows="4" placeholder="Descrição do produto"><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
            </div>

            <!-- Estoque e Botões -->
            <div class="campo-grupo">
                <label for="estoque">Quantidade disponível em estoque</label>
                <div class="compra">
                    <div class="quantidade">
                        <button type="button" id="menos" aria-label="Diminuir estoque">−</button>
                        <input type="number" name="estoque" id="estoque" value="<?php echo $estoque; ?>" min="0" required>
                        <button type="button" id="mais" aria-label="Aumentar estoque">+</button>
                    </div>

                    <button type="submit" class="btn-principal" id="btnSalvar">
                        <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
                    </button>

                    <button type="button" class="btn-secundario" id="btnResetar">
                        <i class="fa-solid fa-rotate-left"></i> Resetar
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>

<script src="script.js"></script>
</body>
</html>
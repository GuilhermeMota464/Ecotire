<?php
include '../../funcoesPHP/connection.php';

// Busca os produtos ordenados pelo id mais recente
$stmt = $pdo->query("SELECT id_produto, nome, preco_custo, preco_venda, preco_promocional, modelo, estoque, imagem, descricao, ativo FROM produtos ORDER BY id_produto DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Produtos - Aruanã</title>
    
    <link rel="stylesheet" href="style.css">
    
    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Icone da aba -->
    <link rel="icon" type="image/png" href="../../assetsGerais/ecotire.webp">
</head>
<body>

<div class="main-content">
    <!-- Cabeçalho -->
    <header>
        <div class="header">
            <div class="header-top">
                <img src="../../assetsGerais/aruana.webp" class="logo" alt="Logo Aruanã" onclick="window.location.href='../inicio-admin/inicio-admin.php'">           
                <img src="../../assetsGerais/fotoPerfil.webp" class="foto_perfil" alt="Foto de Perfil">
            </div>
            <div class="header-bottom">
                <nav>
                    <ul class="menu-horizontal">
                        <li><a onclick="window.location.href='../inicio-admin/inicio-admin.php'">Pedidos</a></li>
                        <li><a onclick="window.location.href='../produtos/produtos-admin.php'" class="ativo">Produtos</a></li>
                        <li><a onclick="window.location.href='../usuarios/usuarios.php'">Usuários</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="title-action-bar">
            <h1>Gerenciamento de Produtos</h1>
            <button id="openModalBtn" class="add-product-btn"><i class="fa-solid fa-plus"></i> Adicionar Produto</button>
        </div>

        <!-- Modal -->
        <div class="modal-backdrop" id="modalBackdrop">
            <div id="productModal" class="modal">
                <button class="modal-close" type="button" id="closeModalBtn">&times;</button>
                
                <form method="post" action="../../funcoesPHP/submit.php" enctype="multipart/form-data" id="form-produto">
                    <div class="modal-column-left">
                        <h2>Cadastrar Produto</h2>
                        
                        <div class="field-container">
                            <input type="text" name="nome" placeholder=" " id="nome" class="input" required>
                            <label for="nome">NOME DO PRODUTO</label>
                        </div>

                        <div class="field-container">
                            <input type="text" name="modelo" placeholder=" " id="modelo" class="input" required>
                            <label for="modelo">MODELO</label>
                        </div>

                        <div class="form-row">
                            <div class="field-container">
                                <input type="number" name="preco_custo" step="0.01" placeholder=" " id="preco_custo" class="input" required>
                                <label for="preco_custo">PREÇO CUSTO (R$)</label>
                            </div>

                            <div class="field-container">
                                <input type="number" name="preco_venda" step="0.01" placeholder=" " id="preco_venda" class="input" required>
                                <label for="preco_venda">PREÇO VENDA (R$)</label>
                            </div>
                        </div>

                        <div class="field-container">
                            <input type="number" name="estoque" placeholder=" " id="estoque" class="input" required>
                            <label for="estoque">ESTOQUE INICIAL</label>
                        </div>

                        <div class="promo-box">
                            <div class="promo-toggle">
                                <input type="checkbox" name="promo" id="promo">
                                <label for="promo">EM PROMOÇÃO?</label>
                            </div>

                            <div class="field-container hidden-field" id="container-preco-promo">
                                <input type="number" name="preco_promocional" step="0.01" placeholder=" " id="preco_promo" class="input">
                                <label for="preco_promo">PREÇO PROMO (R$)</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-column-right">
                        <div class="image-upload-section">
                            <label class="label-img-title">IMAGEM DO PRODUTO</label>
                            <input type="file" name="imagem" accept="image/*" id="inserir-imagem" hidden>
                            <label for="inserir-imagem" class="picture" tabindex="0">
                                <span class="picture-image"><i class="fa-solid fa-cloud-arrow-up"></i><br>Selecione a Imagem</span>
                            </label>
                        </div>

                        <div class="field-container textarea-container">
                            <textarea id="descricao" name="descricao" placeholder=" " required></textarea>
                            <label for="descricao" id="label-descricao">DESCRIÇÃO DO PRODUTO</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">CADASTRAR PRODUTO</button>
                </form>
            </div>
        </div>

        <!-- Grade de Produtos -->
        <main class="product-list">
            <section class="products-grid">
                <?php if (empty($produtos)): ?>
                    <p class="no-products">Nenhum produto cadastrado até o momento.</p>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): 
                        $tem_promo = !empty($produto['preco_promocional']) && $produto['preco_promocional'] > 0 && $produto['preco_promocional'] < $produto['preco_venda'];
                        $preco_final = $tem_promo ? $produto['preco_promocional'] : $produto['preco_venda'];
                        
                        // Exibição da Imagem salva no banco como MEDIUMBLOB (Base64)
                        $src_imagem = '../../assetsGerais/ecotire.webp';
                        if (!empty($produto['imagem'])) {
                            $src_imagem = 'data:image/jpeg;base64,' . base64_encode($produto['imagem']);
                        }
                    ?>
                        <article class="product-card">
                            <div class="image-container">
                                <img src="<?php echo $src_imagem; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            </div>
                            <div class="product-info">
                                <span class="tag-modelo"><?php echo htmlspecialchars($produto['modelo']); ?></span>
                                <h3 class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                
                                <?php if ($tem_promo): 
                                    $desconto = round((($produto['preco_venda'] - $produto['preco_promocional']) / $produto['preco_venda']) * 100);
                                ?>
                                    <p class="price-old">R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></p>
                                    <div class="price-row">
                                        <span class="price-current">R$ <?php echo number_format($produto['preco_promocional'], 2, ',', '.'); ?></span>
                                        <span class="discount-tag"><?php echo $desconto; ?>% OFF</span>
                                    </div>
                                <?php else: ?>
                                    <div class="price-row">
                                        <span class="price-current">R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></span>
                                    </div>
                                <?php endif; ?>

                                <p class="installments">12x de R$ <?php echo number_format($preco_final / 12, 2, ',', '.'); ?> sem juros</p>
                                <p class="stock-info">Estoque: <strong><?php echo $produto['estoque']; ?> un.</strong></p>
                                
                                <div class="div-botoes">
                                    <button type="button" class="edit-button" onclick="window.location.href='../pagina-produto-admin/pagina-produto-admin.php?id=<?php echo $produto['id_produto']; ?>'">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button type="button" class="delete-button" onclick="if(confirm('Tem certeza que deseja excluir o produto \'<?php echo addslashes($produto['nome']); ?>\'?')) window.location.href='../../funcoesPHP/remove.php?delete_id=<?php echo $produto['id_produto']; ?>'">
                                        <i class="fa-solid fa-trash"></i> Excluir
                                    </button>
                                </div>    
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>